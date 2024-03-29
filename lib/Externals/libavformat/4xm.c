/*
 * 4X Technologies .4xm File Demuxer (no muxer)
 * Copyright (c) 2003  The ffmpeg Project
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
 * @file 4xm.c
 * 4X Technologies file demuxer
 * by Mike Melanson (melanson@pcisys.net)
 * for more information on the .4xm file format, visit:
 *   http://www.pcisys.net/~melanson/codecs/
 */

#include "libavutil/intreadwrite.h"
#include "avformat.h"

#define     RIFF_TAG MKTAG('R', 'I', 'F', 'F')
#define  FOURXMV_TAG MKTAG('4', 'X', 'M', 'V')
#define     LIST_TAG MKTAG('L', 'I', 'S', 'T')
#define     HEAD_TAG MKTAG('H', 'E', 'A', 'D')
#define     TRK__TAG MKTAG('T', 'R', 'K', '_')
#define     MOVI_TAG MKTAG('M', 'O', 'V', 'I')
#define     VTRK_TAG MKTAG('V', 'T', 'R', 'K')
#define     STRK_TAG MKTAG('S', 'T', 'R', 'K')
#define     std__TAG MKTAG('s', 't', 'd', '_')
#define     name_TAG MKTAG('n', 'a', 'm', 'e')
#define     vtrk_TAG MKTAG('v', 't', 'r', 'k')
#define     strk_TAG MKTAG('s', 't', 'r', 'k')
#define     ifrm_TAG MKTAG('i', 'f', 'r', 'm')
#define     pfrm_TAG MKTAG('p', 'f', 'r', 'm')
#define     cfrm_TAG MKTAG('c', 'f', 'r', 'm')
#define     ifr2_TAG MKTAG('i', 'f', 'r', '2')
#define     pfr2_TAG MKTAG('p', 'f', 'r', '2')
#define     cfr2_TAG MKTAG('c', 'f', 'r', '2')
#define     snd__TAG MKTAG('s', 'n', 'd', '_')

#define vtrk_SIZE 0x44
#define strk_SIZE 0x28

#define GET_LIST_HEADER() \
    fourcc_tag = get_le32(pb); \
    size = get_le32(pb); \
    if (fourcc_tag != LIST_TAG) \
        return AVERROR_INVALIDDATA; \
    fourcc_tag = get_le32(pb);

typedef struct AudioTrack {
    int sample_rate;
    int bits;
    int channels;
    int stream_index;
    int adpcm;
} AudioTrack;

typedef struct FourxmDemuxContext {
    int width;
    int height;
    int video_stream_index;
    int track_count;
    AudioTrack *tracks;
    int selected_track;

    int64_t audio_pts;
    int64_t video_pts;
    float fps;
} FourxmDemuxContext;

static int fourxm_probe(AVProbeData *p)
{
    if ((AV_RL32(&p->buf[0]) != RIFF_TAG) ||
        (AV_RL32(&p->buf[8]) != FOURXMV_TAG))
        return 0;

    return AVPROBE_SCORE_MAX;
}

static int fourxm_read_header(AVFormatContext *s,
                              AVFormatParameters *ap)
{
    ByteIOContext *pb = s->pb;
    unsigned int fourcc_tag;
    unsigned int size;
    int header_size;
    FourxmDemuxContext *fourxm = s->priv_data;
    unsigned char *header;
    int i;
    int current_track = -1;
    AVStream *st;

    fourxm->track_count = 0;
    fourxm->tracks = NULL;
    fourxm->selected_track = 0;
    fourxm->fps = 1.0;

    /* skip the first 3 32-bit numbers */
    url_fseek(pb, 12, SEEK_CUR);

    /* check for LIST-HEAD */
    GET_LIST_HEADER();
    header_size = size - 4;
    if (fourcc_tag != HEAD_TAG)
        return AVERROR_INVALIDDATA;

    /* allocate space for the header and load the whole thing */
    header = av_malloc(header_size);
    if (!header)
        return AVERROR(ENOMEM);
    if (get_buffer(pb, header, header_size) != header_size)
        return AVERROR(EIO);

    /* take the lazy approach and search for any and all vtrk and strk chunks */
    for (i = 0; i < header_size - 8; i++) {
        fourcc_tag = AV_RL32(&header[i]);
        size = AV_RL32(&header[i + 4]);

        if (fourcc_tag == std__TAG) {
            fourxm->fps = av_int2flt(AV_RL32(&header[i + 12]));
        } else if (fourcc_tag == vtrk_TAG) {
            /* check that there is enough data */
            if (size != vtrk_SIZE) {
                av_free(header);
                return AVERROR_INVALIDDATA;
            }
            fourxm->width = AV_RL32(&header[i + 36]);
            fourxm->height = AV_RL32(&header[i + 40]);

            /* allocate a new AVStream */
            st = av_new_stream(s, 0);
            if (!st)
                return AVERROR(ENOMEM);
            av_set_pts_info(st, 60, 1, fourxm->fps);

            fourxm->video_stream_index = st->index;

            st->codec->codec_type = CODEC_TYPE_VIDEO;
            st->codec->codec_id = CODEC_ID_4XM;
            st->codec->extradata_size = 4;
            st->codec->extradata = av_malloc(4);
            AV_WL32(st->codec->extradata, AV_RL32(&header[i + 16]));
            st->codec->width = fourxm->width;
            st->codec->height = fourxm->height;

            i += 8 + size;
        } else if (fourcc_tag == strk_TAG) {
            /* check that there is enough data */
            if (size != strk_SIZE) {
                av_free(header);
                return AVERROR_INVALIDDATA;
            }
            current_track = AV_RL32(&header[i + 8]);
            if (current_track + 1 > fourxm->track_count) {
                fourxm->track_count = current_track + 1;
                if((unsigned)fourxm->track_count >= UINT_MAX / sizeof(AudioTrack))
                    return -1;
                fourxm->tracks = av_realloc(fourxm->tracks,
                    fourxm->track_count * sizeof(AudioTrack));
                if (!fourxm->tracks) {
                    av_free(header);
                    return AVERROR(ENOMEM);
                }
            }
            fourxm->tracks[current_track].adpcm = AV_RL32(&header[i + 12]);
            fourxm->tracks[current_track].channels = AV_RL32(&header[i + 36]);
            fourxm->tracks[current_track].sample_rate = AV_RL32(&header[i + 40]);
            fourxm->tracks[current_track].bits = AV_RL32(&header[i + 44]);
            i += 8 + size;

            /* allocate a new AVStream */
            st = av_new_stream(s, current_track);
            if (!st)
                return AVERROR(ENOMEM);

            av_set_pts_info(st, 60, 1, fourxm->tracks[current_track].sample_rate);

            fourxm->tracks[current_track].stream_index = st->index;

            st->codec->codec_type = CODEC_TYPE_AUDIO;
            st->codec->codec_tag = 0;
            st->codec->channels = fourxm->tracks[current_track].channels;
            st->codec->sample_rate = fourxm->tracks[current_track].sample_rate;
            st->codec->bits_per_coded_sample = fourxm->tracks[current_track].bits;
            st->codec->bit_rate = st->codec->channels * st->codec->sample_rate *
                st->codec->bits_per_coded_sample;
            st->codec->block_align = st->codec->channels * st->codec->bits_per_coded_sample;
            if (fourxm->tracks[current_track].adpcm)
                st->codec->codec_id = CODEC_ID_ADPCM_4XM;
            else if (st->codec->bits_per_coded_sample == 8)
                st->codec->codec_id = CODEC_ID_PCM_U8;
            else
                st->codec->codec_id = CODEC_ID_PCM_S16LE;
        }
    }

    av_free(header);

    /* skip over the LIST-MOVI chunk (which is where the stream should be */
    GET_LIST_HEADER();
    if (fourcc_tag != MOVI_TAG)
        return AVERROR_INVALIDDATA;

    /* initialize context members */
    fourxm->video_pts = -1;  /* first frame will push to 0 */
    fourxm->audio_pts = 0;

    return 0;
}

static int fourxm_read_packet(AVFormatContext *s,
                              AVPacket *pkt)
{
    FourxmDemuxContext *fourxm = s->priv_data;
    ByteIOContext *pb = s->pb;
    unsigned int fourcc_tag;
    unsigned int size, out_size;
    int ret = 0;
    int track_number;
    int packet_read = 0;
    unsigned char header[8];
    int audio_frame_count;

    while (!packet_read) {

        if ((ret = get_buffer(s->pb, header, 8)) < 0)
            return ret;
        fourcc_tag = AV_RL32(&header[0]);
        size = AV_RL32(&header[4]);
        if (url_feof(pb))
            return AVERROR(EIO);
        switch (fourcc_tag) {

        case LIST_TAG:
            /* this is a good time to bump the video pts */
            fourxm->video_pts ++;

            /* skip the LIST-* tag and move on to the next fourcc */
            get_le32(pb);
            break;

        case ifrm_TAG:
        case pfrm_TAG:
        case cfrm_TAG:
        case ifr2_TAG:
        case pfr2_TAG:
        case cfr2_TAG:
        {

            /* allocate 8 more bytes than 'size' to account for fourcc
             * and size */
            if (size + 8 < size || av_new_packet(pkt, size + 8))
                return AVERROR(EIO);
            pkt->stream_index = fourxm->video_stream_index;
            pkt->pts = fourxm->video_pts;
            pkt->pos = url_ftell(s->pb);
            memcpy(pkt->data, header, 8);
            ret = get_buffer(s->pb, &pkt->data[8], size);

            if (ret < 0)
                av_free_packet(pkt);
            else
                packet_read = 1;
            break;
        }

        case snd__TAG:
            track_number = get_le32(pb);
            out_size= get_le32(pb);
            size-=8;

            if (track_number == fourxm->selected_track) {
                ret= av_get_packet(s->pb, pkt, size);
                if(ret<0)
                    return AVERROR(EIO);
                pkt->stream_index =
                    fourxm->tracks[fourxm->selected_track].stream_index;
                pkt->pts = fourxm->audio_pts;
                packet_read = 1;

                /* pts accounting */
                audio_frame_count = size;
                if (fourxm->tracks[fourxm->selected_track].adpcm)
                    audio_frame_count -=
                        2 * (fourxm->tracks[fourxm->selected_track].channels);
                audio_frame_count /=
                      fourxm->tracks[fourxm->selected_track].channels;
                if (fourxm->tracks[fourxm->selected_track].adpcm)
                    audio_frame_count *= 2;
                else
                    audio_frame_count /=
                    (fourxm->tracks[fourxm->selected_track].bits / 8);
                fourxm->audio_pts += audio_frame_count;

            } else {
                url_fseek(pb, size, SEEK_CUR);
            }
            break;

        default:
            url_fseek(pb, size, SEEK_CUR);
            break;
        }
    }
    return ret;
}

static int fourxm_read_close(AVFormatContext *s)
{
    FourxmDemuxContext *fourxm = s->priv_data;

    av_free(fourxm->tracks);

    return 0;
}

AVInputFormat fourxm_demuxer = {
    "4xm",
    NULL_IF_CONFIG_SMALL("4X Technologies format"),
    sizeof(FourxmDemuxContext),
    fourxm_probe,
    fourxm_read_header,
    fourxm_read_packet,
    fourxm_read_close,
};
