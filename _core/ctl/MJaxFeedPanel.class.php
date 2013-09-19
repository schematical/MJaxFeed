<?php
class MJaxFeedPanel extends MJaxPanel{
    protected static $arrFeedEntityControls = array();
    public $arrFeedEntities = array();
    protected $intLastUpdated = 0;
    public function __construct($objParentControl, $strControlId = null){
        parent::__construct($objParentControl, $strControlId);
        $this->strTemplate = __MJAX_FEED_CORE_VIEW__ . '/' . get_class($this) . '.tpl.php';

        krsort($this->arrFeedEntities, SORT_NUMERIC);
        if(count($this->arrFeedEntities)){
            $arrKeys = array_keys($this->arrFeedEntities);

            $this->intLastUpdated = $arrKeys[0];
        }



        /*$this->AddAction(
            new MJaxTimeoutEvent(5000),
            new MJaxServerControlAction($this, 'Update')
        );*/
    }
    public static function SetFeedEntityCtl($strEntity, $strControlClass){
        self::$arrFeedEntityControls[$strEntity] = $strControlClass;
    }
    public static function GetFeedEntityCtl($objFeedEntity){
        $strEntity = get_class($objFeedEntity);
        if(
            ($strEntity == 'MLCCBaseEntityCollection') &&
            ($objFeedEntity->length() > 0)
        ){
            $strEntity = get_class($objFeedEntity[0]);
        }
        if(array_key_exists($strEntity, self::$arrFeedEntityControls)){
            $strClass = self::$arrFeedEntityControls[$strEntity];
            return $strClass;
        }else{
            return null;
        }
    }
    public function AddFeedEntity($mixFeedEntity, $strDateField = 'CreDate', $mixOrigData = null){
            if(is_null($mixFeedEntity)){
                return null;
            }
            //$this->strTemplate = __VIEW_ACTIVE_APP_DIR__ . '/www/_FFSFeedForm.tpl.php';
            if(
                ((is_object($mixFeedEntity)) && is_null($mixOrigData)) &&
                (
                    ($mixFeedEntity instanceof BaseEntityCollection) ||
                    ($mixFeedEntity instanceof MLCBaseEntityCollection)
                )
            ){
                $mixFeedEntity = $mixFeedEntity->getCollection();
            }

            if(is_array($mixFeedEntity)){
                if(is_null($mixOrigData)){
                    foreach($mixFeedEntity as $intIndex => $objFeedEntity){
                        $this->AddFeedEntity($objFeedEntity, $strDateField, $mixFeedEntity);
                    }
                    return;
                }else{
                    $mixOrigData = $mixFeedEntity;
                    $mixFeedEntity = array_values($mixFeedEntity)[0];

                }
            }

            try{

                $intTime = strtotime($mixFeedEntity->$strDateField);
            }catch(Exception $e){
                throw $e;
                throw new Exception("Objects passed in to this method must have a '" . $strDateField . "'");
            }

            if(is_numeric($intTime)){
                while(array_key_exists($intTime, $this->arrFeedEntities)){
                    $intTime += 1;
                }
                $strClass = $this->GetFeedEntityCtl($mixFeedEntity, $mixOrigData);

                $this->arrFeedEntities[$intTime] = new $strClass($this, $mixFeedEntity);
            }else{
                //_dv($mixFeedEntity);
                throw new Exception("Invalid time to sort by");
            }
            return $this->arrFeedEntities[$intTime];

        }
    public function Pre_Render(){
        _dv(array_keys($this->arrFeedEntities));
        krsort($this->arrFeedEntities, SORT_NUMERIC);
        foreach($this->arrFeedEntities as $pnlFeedEntite){
            $this->AddWidget('x','x', $pnlFeedEntite);
        }
    }
    
}