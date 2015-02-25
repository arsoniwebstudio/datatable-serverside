<?php

use Illuminate\Routing\Controller;
 
abstract class DtController extends Controller {
 
  abstract protected function getModel();
  abstract protected function setDTColums($dataTable);
  
  protected function getDT($timestamps = true)
  {
  	$model = $this->getModel();
  	return $model::datatable($timestamps = true);
  }
  
  public function datatable($timestamps = true)
  {
  	$dataTable = $this -> getDT($timestamps = true);
  	$dataTable = $this -> setDTColums($dataTable);
  	return $dataTable->make();
  }
  
}