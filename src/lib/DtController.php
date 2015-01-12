<?php

use Illuminate\Routing\Controller;
 
abstract class DtController extends Controller {
 
  abstract protected function getModel();
  abstract protected function setDTColums($dataTable);
  
  protected function getDT()
  {
  	$model = $this->getModel();
  	return $model::datatable();
  }
  
  public function datatable()
  {
  	$dataTable = $this -> getDT();
  	$dataTable = $this -> setDTColums($dataTable);
  	return $dataTable->make();
  }
  
}