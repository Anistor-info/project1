/*
 * American Laser Games MM Format Demuxer
 * Copyright (c) 2006 Peter Ross
 *
 * This file is part of FFmpeg.
 *
 * FFmpeg is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * FFmpeg is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with FFmpeg; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 */

/**
 * @file mm.c
 * American Laser Games MM Format Demuxer
 * by Peter Ross (suxen_drol at hotmail dot com)
 *
 * The MM format was used by IBM-PC ports of ALG's "arcade shooter" games,
 * including Mad Dog McCree and Crime Patrol.
 *
 * Technical details here:
 *  http://wiki.multimedia.cx/index.php?title=American_Laser_Games_MM
 */

#include "libavutil/intreadwrite.h"
#include "avformat.h"

#define MM_PREAMBLE_SIZE    6

#define MM_TYPE_HEADER      0x0
#define MM_TYPE_INTER       0x5
#define MM_TYPE_INTRA       0x8
#define MM_TYPE_INTRA_HH    0xc
#define MM_TYPE_INTER_HH    0xd
#define MM_TYPE_INTRA_HHV   0xe
#define MM_TYPE_INTER_HHV   0xf
#define MM_TYPE_AUDIO       0x15
#define MM_TYPE_PALETTE     0x31

#define MM_HEADER_LEN_V     0x16    /* video only */
#define MM_HEADER_LEN_AV    0x18    /* video + audio */

#define MM_PALETTE_COUNT    128
#define MM_PALETTE_SIZE     (MM_PALETTE_COUNT*3)

typedef struct {
  unsigned int audio_pts, video_pts;
} MmDemuxContext;

static int mm_probe(AVProbeData *p)
{
    /* the first chunk is always the header */
    if (AV_RL16(&p->buf[0]) != MM_TYPE_HEADER)
        return 0;
    if (AV_RL32(&p->buf[2]) != MM_HEADER_LEN_V && AV_RL32(&p->buf[2]) != MM_HEADER_LEN_AV)
        return 0;

    /* only return half certainty since this check is a bit sketchy */
    return AVPROBE_SCORE_MAX / 2;
}

static int mm_read_header(AVFormatContext *s,
                           AVFormatParameters *ap)
{
    MmDemuxContext *mm = s->priv_data;
    ByteIOContext *pb = s->pb;
    AVStream *st;

    unsigned int type, length;
    unsigned int frame_rate, width, height;

    type = get_le16(pb);
    length = get_le32(pb);

    if (type != MM_TYPE_HEADER)
        return AVERROR_INVALIDDATA;

    /* read header */
    get_le16(pb);   /* total number of chunks */
    frame_rate = get_le16(pb);
    get_le16(pb);   /* ibm-pc video bios mode */
    width = get_le16(pb);
    height = get_le16(pb);
    url_fseek(pb, length - 10, SEEK_CUR);  /* unknown data */

    /* video stream */
    st = av_new_stream(s, 0);
    if (!st)
        return AVERROR(ENOMEM);
    st->codec->codec_type = CODEC_TYPE_VIDEO;
    st->codec->codec_id = CODEC_ID_MMVIDEO;
    st->codec->codec_tag = 0;  /* no fourcc */
    st->codec->width = width;
    st->codec->height = height;
    av_set_pts_info(st, 64, 1, frame_rate);

    /* audio stream */
    if (length == MM_HEADER_LEN_AV) {
        st = av_new_stream(s, 0);
        if (!st)
            return AVERROR(ENOMEM);
        st->codec->codec_type = CODEC_TYPE_AUDIO;
        st->codec->codec_tag = 0; /* no fourcc */
        st->codec->codec_id = CODEC_ID_PCM_U8;
        st->codec->channels = 1;
        st->codec->sample_rate = 8000;
        av_set_pts_info(st, 64, 1, 8000); /* 8000 hz */
    }

    mm->audio_pts = 0;
    mm->video_pts = 0;
    return 0;
}

static int mm_read_packet(AVFormatContext *s,
                           AVPacket *pkt)
{
    MmDemuxContext *mm = s->priv_data;
    ByteIOContext *pb = s->pb;
    unsigned char preamble[MM_PREAMBLE_SIZE];
    unsigned int type, length;

    while(1) {

        if (get_buffer(pb, preamble, MM_PREAMBLE_SIZE) != MM_PREAMBLE_SIZE) {
            return AVERROR(EIO);
        }

        type = AV_RL16(&preamble[0]);
        length = AV_RL16(&preamble[2]);

        switch(type) {
        case MM_TYPE_PALETTE :
        case MM_TYPE_INTER :
        case MM_TYPE_INTRA :
        case MM_TYPE_INTRA_HH :
        case MM_TYPE_INTER_HH :
        case MM_TYPE_INTRA_HHV :
        case MM_TYPE_INTER_HHV :
            /* output preamble + data */
            if (av_new_packet(pkt, length + MM_PREAMBLE_SIZE))
                return AVERROR(ENOMEM);
            memcpy(pkt->data, preamble, MM_PREAMBLE_SIZE);
            if (get_buffer(pb, pkt->data + MM_PREAMBLE_SIZE, length) != length)
                return AVERROR(EIO);
            pkt->size = length + MM_PREAMBLE_SIZE;
            pkt->stream_index = 0;
            pkt->pts = mm->video_pts;
            if (type!=MM_TYPE_PALETTE)
                mm->video_pts++;
            return 0;

        case MM_TYPE_AUDIO :
            if (av_get_packet(s->pb, pkt, length)<0)
                return AVERROR(ENOMEM);
            pkt->size = length;
            pkt->stream_index = 1;
            pkt->pts = mm->audio_pts++;
            return 0;

        default :
            av_log(NULL, AV_LOG_INFO, "mm: unknown chunk type 0x%x\n", type);
            url_fseek(pb, length, SEEK_CUR);
        }
    }

    return 0;
}

AVInputFormat mm_demuxer = {
    "mm",
    NULL_IF_CONFIG_SMALL("American Laser Games MM format"),
    sizeof(MmDemuxContext),
    mm_probe,
    mm_read_header,
    mm_read_packet,
};
