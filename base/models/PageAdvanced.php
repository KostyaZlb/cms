<?php
/**
 * Расширенная модель страницы сайта.
 *
 * У каждой страницы добавляется, возможности:
 *  - добавлять главный image
 *  - добавлять второстепенный image_cover
 *  - добавлять много images
 *  - добавлять много files
 *  - можно голлосовать
 *  - можно комментировать
 *  - можно подписываться
 *  - добавляется полное и краткое описание страницы
 *
 *
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010-2014 SkeekS (Sx)
 * @date 31.10.2014
 * @since 1.0.0
 */
namespace skeeks\cms\base\models;

use skeeks\cms\models\behaviors\HasComments;
use skeeks\cms\models\behaviors\HasFiles;
use skeeks\cms\models\behaviors\HasSubscribes;
use skeeks\cms\models\behaviors\HasVotes;
use skeeks\cms\models\behaviors\Implode;

use skeeks\cms\models\behaviors\traits\HasFiles as THasFiles;
use skeeks\cms\models\behaviors\traits\HasSubscribes as THasSubscribes;
use skeeks\cms\models\behaviors\traits\HasVotes as THasVotes;
use skeeks\cms\models\behaviors\traits\HasComments as THasComments;

use Yii;

/**
 * @property string $description_short
 * @property string $description_full
 * @property string $image
 * @property string $image_cover
 * @property string $images
 * @property string $files
 * @property integer $count_comment
 * @property integer $count_subscribe
 * @property string $users_subscribers
 * @property integer $count_vote
 * @property integer $result_vote
 * @property string $users_votes_up
 * @property string $users_votes_down
 *
 * Class PageAdvanced
 * @package skeeks\cms\base\models
 */
abstract class PageAdvanced extends Page
{
    protected $_maxCountFiles           = 100;
    protected $_maxCountImages          = 100;
    protected $_maxCountImage           = 1;
    protected $_maxCountImageCover      = 1;

    use THasComments;
    use THasSubscribes;
    use THasVotes;
    use THasFiles;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [

            HasComments::className(),
            HasSubscribes::className(),
            HasVotes::className(),

            [
                "class"  => Implode::className(),
                "fields" =>  [
                    "users_subscribers", "users_votes_up", "users_votes_down",
                    "image_cover", "image", "images", "files"
                ]
            ],

            [
                "class"  => HasFiles::className(),
                "fields" =>
                [
                    "image" =>
                    [
                        //HasFiles::MAX_SIZE_TOTAL      => 1*1024, //1Mb
                        HasFiles::MAX_SIZE            => 1*1024, //1Mb
                        HasFiles::ALLOWED_EXTENSIONS  => ['jpg', 'jpeg', 'png', 'gif'],
                        HasFiles::MAX_COUNT_FILES     => $this->_maxCountImage,
                        HasFiles::ACCEPT_MIME_TYPE    => "image/*",
                    ],

                    "image_cover" =>
                    [
                        //HasFiles::MAX_SIZE_TOTAL      => 1*1024, //1Mb
                        HasFiles::MAX_SIZE            => 1*1024, //1Mb
                        HasFiles::ALLOWED_EXTENSIONS  => ['jpg', 'jpeg', 'png', 'gif'],
                        HasFiles::MAX_COUNT_FILES     => $this->_maxCountImageCover,
                        HasFiles::ACCEPT_MIME_TYPE    => "image/*",
                    ],

                    "images" =>
                    [
                        //HasFiles::MAX_SIZE_TOTAL      => 15*1024, //1Mb
                        HasFiles::MAX_SIZE            => 1*1024, //1Mb
                        HasFiles::ALLOWED_EXTENSIONS  => ['jpg', 'jpeg', 'png', 'gif'],
                        HasFiles::MAX_COUNT_FILES     => $this->_maxCountImages,
                        HasFiles::ACCEPT_MIME_TYPE    => "image/*",
                    ],

                    "files" =>
                    [
                        //HasFiles::MAX_SIZE_TOTAL      => 15*1024, //1Mb
                        HasFiles::MAX_SIZE            => 1*1024, //1Mb
                        HasFiles::MAX_COUNT_FILES     => $this->_maxCountFiles,
                    ],
                ]
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'image' => Yii::t('app', 'Image'),
            'image_cover' => Yii::t('app', 'Image Cover'),
            'images' => Yii::t('app', 'Images'),
            'files' => Yii::t('app', 'Files'),
            'count_comment' => Yii::t('app', 'Count Comment'),
            'count_subscribe' => Yii::t('app', 'Count Subscribe'),
            'users_subscribers' => Yii::t('app', 'Users Subscribers'),
            'count_vote' => Yii::t('app', 'Count Vote'),
            'result_vote' => Yii::t('app', 'Result Vote'),
            'users_votes_up' => Yii::t('app', 'Users Votes Up'),
            'users_votes_down' => Yii::t('app', 'Users Votes Down'),
            'description_short' => Yii::t('app', 'Description Short'),
            'description_full' => Yii::t('app', 'Description Full'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['description_short', 'description_full'], 'string'],
            [["users_subscribers", "users_votes_up", "users_votes_down"], 'safe'],
            [["images", "files", "image_cover", "image"], 'safe'],
            [['count_comment', 'count_subscribe', 'count_vote'], 'integer'],
        ]);
    }


    /**
     * @return array
     */
    public function getImages()
    {
        return (array) $this->images;
    }

    /**
     * @return string
     */
    public function getMainImage()
    {
        if ($this->image)
        {
            return (string) array_shift($this->image);
        }

        return \Yii::$app->params["noimage"];
    }

    /**
     * @return string
     */
    public function getImageCover()
    {
        if ($this->image)
        {
            return (string) array_shift($this->image_cover);
        }

        return \Yii::$app->params["noimage"];
    }
}