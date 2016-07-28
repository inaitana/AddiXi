<?php
class Admin_Model_Immagini {
    protected $_cutImagesDb;
    protected $_config;

    public function __construct()
    {
        $this->_cutImagesDb = new Admin_Model_DbTable_ImmaginiTagliate();
        $this->_config = Zend_Registry::get('config');
    }

    public function getImage($id)
    {
        return $this->_cutImagesDb->find($id)->current();
    }
    
    public function addImage($image_path)
    {
        $nomeFile = substr($image_path, strrpos($image_path, '/') + 1);

        $thumbnail_base64enc = $this->getBase64(realpath(APPLICATION_PATH ."/../public/.thumbs").$image_path);
        
        // Non memorizzo base64 più lunghi di 32k
        if(strlen($thumbnail_base64enc) > pow(2,15))
            $thumbnail_base64enc = null;

        return $this->_cutImagesDb->insert($nomeFile, $image_path, $thumbnail_base64enc);
    }

    public function renameImage($source, $dest)
    {
        return $this->_cutImagesDb->rename($source, $dest);
    }

    public function copyImage($source, $dest)
    {
        return $this->_cutImagesDb->copy($source, $dest);
    }

    public function deleteImage($path)
    {
    	return $this->_cutImagesDb->delete($path);
    }

    public function renameDir($source, $dest)
    {
    	return $this->_cutImagesDb->renameDir($source, $dest);
    }

    public function deleteDir($dirPath)
    {
		$this->deleteDirectory(realpath(APPLICATION_PATH ."/../public/.thumbs").$dirPath);
    	return $this->_cutImagesDb->deleteDir($dirPath);
    }
    
    public function findImageByPath($path)
    {
        return $this->_cutImagesDb->fetchRow(array('Path = ?' => $path));
    }
    
    public function getPathsFromDb()
    {
    	return $this->_cutImagesDb->getPathsFromDb();
    }
    
    public function getPathsFromFs()
    {
    	return $this->getFiles(realpath(APPLICATION_PATH ."/../public/immagini"));
    }

    protected function getBase64($filename)
    {
        return base64_encode(file_get_contents($filename));
    }

    protected function deleteDirectory($dir)
    {
    	if (is_dir($dir)) {
     		$objects = scandir($dir);
     		foreach ($objects as $object)
				if ($object != "." && $object != "..")
        			if (filetype($dir."/".$object) == "dir")
        				$this->deleteDirectory($dir."/".$object); 
        			else 
        				@unlink($dir."/".$object);
		    reset($objects);
		    @rmdir($dir);
     	} 
    }
    
    protected function getFiles($path)
    {
    	$files = array();
    	$relPath = str_replace(realpath(APPLICATION_PATH ."/../public"),"",$path)."/";
    	$scan = scandir($path);
    	
    	for($i=0;$i<count($scan);$i++)
    	{
    		if($scan[$i] != '.' && $scan[$i] != '..')
    		{
    			if(strpos($scan[$i], ".") === FALSE)
    				$files = array_merge($files,$this->getFiles($path."/".$scan[$i]));
    			else
    				$files[$i] = $relPath.$scan[$i];
    		}
    	}
    	    	
    	return $files;
    }
}
?>
