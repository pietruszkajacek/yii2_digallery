<?php

namespace frontend\models;

use yii\base\Model;
use Yii;
use common\helpers\TagHelper;
use frontend\components\TagWithCategory;
use yii\web\UploadedFile;
use yii\imagine\Image as ImageImagine;
use frontend\components\UrlSlugHelper;

/**
 * Image submit form
 */
class ImageSubmitForm extends Model
{
    public $title;
    public $category_id;
    public $description;
    public $can_comment;
    public $can_evaluated;
    public $plus_18;
    public $tags;
    public $image;
    
    private $_stack = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'gif, jpg, png'],
            [['image'], 'image', 'minWidth' => 1, 'maxWidth' => 10000, 'minHeight' => 1, 'maxHeight' => 10000],
            ['title', 'filter', 'filter' => 'trim'],
            ['title', 'required'],
            ['title', 'string', 'min' => 2, 'max' => 255],
            [['description', 'tags'], 'safe'],
            [['can_comment', 'can_evaluated', 'plus_18'], 'boolean', 'skipOnEmpty' => false],
            ['category_id', 'in', 'range' => ImageNestedSetCategory::find()->select('id')->asArray()->column(), 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image' => 'Praca',
            'title' => 'Tytuł',
            'description' => 'Opis',
            'can_comment' => 'pracę będzie można komentować.',
            'can_evaluated' => 'pracę będzie można oceniać.',
            'plus_18' => 'praca zawiera treści przeznaczone wyłącznie dla osób pełnoletnich.',
            'category_id' => 'Kategoria',
            'tags' => 'Tagi'
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['edit'] = ['title', 'category_id', 'description', 'can_comment', 'can_evaluated', 'plus_18', 'tags'];

        return $scenarios;
    }

    public function prepareTags() 
    {
        //TODO: operacje na tagach wrzucic do frontend/components/TaggableCategoryBehavior.php
        $userTags = TagHelper::splitTags(mb_strtolower($this->tags, 'UTF-8'));
        $tags = array_unique(array_merge(TagHelper::splitTags(mb_strtolower($this->title . ' ' . $this->description, 'UTF-8')), $userTags));

        $tagsWithCategory = [];

        foreach ($tags as $tag) {
            $tagsWithCategory[] = new TagWithCategory(['name' => $tag, 'category' => in_array($tag, $userTags) ? 1 : 0]);
        }
        
        return $tagsWithCategory;
    }
    
    public function updateImage($image)
    {
        $updated = false;

        if ($this->validate()) {
            $image->title = $this->title;
            $image->description = $this->description;
            $image->can_comment = $this->can_comment;
            $image->can_evaluated = $this->can_evaluated;
            $image->plus_18 = $this->plus_18;
            $image->category_id = $this->category_id;
            $image->setTagsWithCategory($this->prepareTags());

            $updated = $image->save();
        }

        return $updated;
    }

    public function generateImageFileName()
    {
        return UrlSlugHelper::urlSlug($this->image->baseName) . '-' . Yii::$app->security->generateRandomString(6);
    }

    public function submitImage()
    {
        $submitOK = false;
        
        $this->image = UploadedFile::getInstance($this, 'image');
        // Walidacja fromularza oraz prawidłowego wgrania pliku pracy
        if ($this->image && $this->validate()) {
            // Wygeneruj nazwe pliku
            $imageFileName = $this->generateImageFileName();
            // Zapis pliku pracy
            if ($this->image->saveAs(Yii::getAlias('@uploads-images') . '/' . $imageFileName . '.' . $this->image->extension)) {
                // Zapisz nazwę pliku na stos
                array_push($this->_stack, Yii::getAlias('@uploads-images') . '/' . $imageFileName . '.' . $this->image->extension);
                
                try {
                    $this->generateThumbs($imageFileName . '.' . $this->image->extension);
                    
                    $image = new Image();
                    
                    $image->user_id = 1;
                    
                    $image->file_name = $imageFileName;
                    $image->file_type = $this->image->type;
                    $image->file_ext = $this->image->extension;
                    $image->file_size = $this->image->size;
                    $image->file_type = $this->image->type;
                    $image->base_name = $this->image->baseName;
                    
                    $img = ImageImagine::getImagine()->open(Yii::getAlias('@uploads-images') . '/' . $imageFileName . '.' . $this->image->extension);
                    $size = $img->getSize();
                    
                    unset($img);
                    
                    $image->width = $size->getWidth();
                    $image->height = $size->getHeight();
                    unset($size);
                    
                    $image->title = $this->title;
                    $image->description = $this->description;
                    $image->category_id = $this->category_id;
                    $image->can_comment = $this->can_comment;
                    $image->can_evaluated = $this->can_evaluated;
                    $image->plus_18 = $this->plus_18;
                    $image->setTagsWithCategory($this->prepareTags());
                            
                    // zapis do bazy
                    if ($image->save()) {
                        $submitOK = true;
                    } else {
                        throw new \RuntimeException();
                    }
                    
                } catch (\RuntimeException $e) {
                    
                    while ( ! empty($this->stack)) {
                        unlink(array_pop($this->stack));
                    }

                }  
            }
        } 
        
        return $submitOK;
    }

    public function generateThumbs($imageFileName)
    {
        $thumbMini = ImageImagine::thumbnail(Yii::getAlias('@uploads-images') . '/' . $imageFileName, 100, 100);
        $thumbMini->save(Yii::getAlias('@uploads-thumbs-mini') . '/' . $imageFileName);
        unset($thumbMini);
        $this->_stack[] = Yii::getAlias('@uploads-thumbs-mini') . '/' . $imageFileName;

        $thumbSmall = ImageImagine::thumbnail(Yii::getAlias('@uploads-images') . '/' . $imageFileName, 250, 250);
        $thumbSmall->save(Yii::getAlias('@uploads-thumbs-small') . '/' . $imageFileName);
        unset($thumbSmall);
        $this->_stack[] = Yii::getAlias('@uploads-thumbs-small') . '/' . $imageFileName;

        $thumbPreview = ImageImagine::thumbnail(Yii::getAlias('@uploads-images') . '/' . $imageFileName, 650, 650);
        $thumbPreview->save(Yii::getAlias('@uploads-thumbs-preview') . '/' . $imageFileName);
        unset($thumbPreview);
        $this->_stack[] = Yii::getAlias('@uploads-thumbs-preview') . '/' . $imageFileName;
    }
}
