<?php
    class Uploader
    {
        private $destinationPath;
        private $errorMessage;
        private $extensions;
        private $allowAll;
        private $maxSize;
        private $uploadName;
        private $imageSeq;
        private $sameName = true;
       

        function setDir($path){
            $this->destinationPath  =   $path;
            $this->allowAll =   false;
        }

        function allowAllFormats(){
            $this->allowAll =   true;
        }

        function setMaxSize($sizeMB){
            $this->maxSize  =   $sizeMB * (1024*1024);
        }

        function setExtensions($options){
            $this->extensions   =   $options;
        }

        function setSameFileName($true){
            $this->sameName =$true;
        }
        function getExtension($string){
            $ext="";
            try{
                $parts=explode(".",$string);
                $ext=strtolower($parts[count($parts)-1]);
            } catch (Exception $c){
                $ext="";
            }
            return $ext;
        }

        function setMessage($message){
            $this->errorMessage =   $message;
        }

        function getMessage(){
            return $this->errorMessage;
        }

        function getUploadName(){
            return $this->uploadName;
        }
        function setSequence($seq){
            $this->imageSeq=$seq;
        }

        function getRandom(){
            return strtotime(date('Y-m-d H:i:s')).rand(1111,9999).rand(11,99).rand(111,999);
        }
       
        function uploadFile($fileBrowse){
            $result =   false;
            $size   =   $fileBrowse["size"];
            $name   =   $fileBrowse["name"];
            $ext    =   $this->getExtension($name);
            if(!is_dir($this->destinationPath)){
                $this->setMessage("El destino no es un directorio ");
            }else if(!is_writable($this->destinationPath)){
                $this->setMessage("No se puede escribir en el destino");
            }else if(empty($name)){
                $this->setMessage("Fichero no seleccionado");
            }else if($size>$this->maxSize){
                $this->setMessage("Fichero demasiado grande");
            }else if($this->allowAll || (!$this->allowAll && in_array($ext,$this->extensions))){

                if($this->sameName==false){
                    $this->uploadName   =  $this->imageSeq."-".substr(md5(rand(1111,9999)),0,8).$this->getRandom().rand(1111,1000).rand(99,9999).".".$ext;
                } else {
                    $this->uploadName=  $name;
                }
                if(move_uploaded_file($fileBrowse["tmp_name"],$this->destinationPath.$this->uploadName)){
                    $result = true;
                } else {
                    $this->setMessage("La subida ha fallado, prueba mas tarde");
                }
            } else {
                $this->setMessage("Formato no válido");
            }
            return $result;
        }

        function deleteUploaded(){
            unlink($this->destinationPath.$this->uploadName);
        }

    }

?>