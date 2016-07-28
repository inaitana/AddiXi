<?php

class Admin_Model_DbTable_ImmaginiTagliate extends Application_Model_DbTable_ImmaginiTagliate
{
    protected $_imagesDb;

    public function init()
    {
        $this->_imagesDb = new Application_Model_DbTable_Immagini();
        parent::init();
    }

    public function insert($nomeFile, $image_path, $thumbnail_base64enc)
    {
        $imageData['Nome'] = $nomeFile;
        $imageData['Path'] = $image_path;
        
        $idImmagine = $this->_imagesDb->insert($imageData);

        $cutImageData['idImmagine'] = $idImmagine;
        $cutImageData['Path'] = $image_path;
        $cutImageData['Thumbnail Base64'] = $thumbnail_base64enc;
        return parent::insert($cutImageData);
    }

    public function rename($source, $dest)
    {
        $where = array('Path = ?' => $source);
        $data = array('Nome' => substr($dest, strrpos($dest, '/') + 1), 'Path' => $dest);

        $this->_imagesDb->update($data, $where);

        unset($data['Nome']);
        return $this->update($data, $where);
    }

    public function copy($source, $dest)
    {
        $imageData = $this->_imagesDb->fetchRow(array('Path = ?' => $source))->toArray();
        unset($imageData['idImmagine']);
        $imageData['Nome'] = substr($dest, strrpos($dest, '/') + 1);
        $imageData['Path'] = $dest;
        $idImmagine = $this->_imagesDb->insert($imageData);

        $cutImageData = $this->fetchRow(array('Path = ?' => $source))->toArray();
        unset($cutImageData['idImmagineTagliata']);
        $cutImageData['idImmagine'] = $idImmagine;
        $cutImageData['Path'] = $dest;
        return parent::insert($cutImageData);
    }

    public function delete($path)
    {
        $where = array('Path = ?' => $path);
        parent::delete($where);
        return $this->_imagesDb->delete($where);
    }

    public function renameDir($source, $dest)
    {
        $data = array('Path' => new Zend_Db_Expr("REPLACE(Path, '".$source."', '".$dest."')"));
        $where = array('Path LIKE ?' => $source."/%");
        $this->_imagesDb->update($data, $where);
        return $this->update($data, $where);
    }

    public function deleteDir($dirPath)
    {
        $where = array('Path LIKE ?' => $dirPath."/%");
        parent::delete($where);
        return $this->_imagesDb->delete($where);
    }
    
    public function getPathsFromDb()
    {
    	$select = $this->select()->from($this, array('idImmagineTagliata','Path'));
    	$result = $this->_db->fetchPairs($select);
    	
    	$unique = array_unique($result);
    	$duplicates = array_diff_key($result, $unique);
    	
    	foreach($duplicates as $duplicateId => $duplicateValue)
    	{
    		$duplicateRow = $this->find($duplicateId);
    		$originalId = array_search($duplicateValue, $unique);
    		
    		foreach($this->getDependentTables() as $dependentTable)
    		{
    			$dependentRows = $duplicateRow->findDependentRowset($dependentTable);
				foreach($dependentRows as $row)
				{
					$row->idImmagineTagliata = $originalId;
					$row->save();
				}    			
    		}
    		$duplicateImageId = $duplicateRow['idImmagine'];
    		$duplicateRow->delete();
    		$this->_imagesDb->delete(array('idImmagine = ?' => $duplicateImageId));
    	}
    	
    	return $unique;
    }
}
