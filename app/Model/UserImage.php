<?php 
class UserImage extends AppModel {
/**
 * name property
 *
 * @var string 'UserImage'
 * @access public
 */
    var $name = 'UserImage';
/**
 * displayField property
 *
 * @var string 'description'
 * @access public
 */
    var $displayField = 'description';
/**
 * validate property
 *
 * @var array
 * @access public
 */
    var $validate = array(
        'user_id' => array('numeric'),
        'model' => array('alphaNumeric'),
        'foreign_key' => array(
            'missing' => array('rule' => array('notEmpty'))
        ),
    );
/**
 * actsAs property
 *
 * @var array
 * @access public
 */
    var $actsAs = array(
        'Polymorphic',
        'ImageUpload' => array(
            //FIXME: Flash does not supply proper MIME types for payload but rather defaults to application/octet-stream
            'allowedMime' => array('image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'application/octet-stream'),
            'dirFormat' => 'user-images{DS}{$id}',
            'overwriteExisting' => true,
            'dirField' => false,
            'versions' => array(
                'large' => array(
                    'vBaseDir' => '{IMAGES}',
                    'vDirFormat' => '{dirFormat}',
                    'vFileFormat' => '{$filenameOnly}_large.{$extension}',
                    'callback' => array('resize', 280, 325)
                ),
                'xlarge' => array(
                    'vBaseDir' => '{IMAGES}',
                    'vDirFormat' => '{dirFormat}',
                    'vFileFormat' => '{$filenameOnly}_xlarge.{$extension}',
                    'callback' => array('resize', 450, 450)
                )
            )),
        'Slugged'
    );
/**
 * belongsTo property
 *
 * @var array
 * @access public
 */
    var $belongsTo = array('User');

}
?> 