<?php
class CRMQuoteProduct
{
    public $quantity = "";
    public $quantitycode = "ZPC";  // not available in interface defaulting to 1000pc
    public $application = "";
    public $requiredby = "";
    public $diagram = null; // this is a file tbd
    public $partnumber = "";
    public $comments = "";
    public $product = ""; // product of interest
    public $dimensions = "";
    public $dimensionscode = ""; //mm||inches
    // required in SAP method, but not available in interface
    public $dimid = "";
    public $dimidcode = "INH";
    public $dimod = "";
    public $dimodcode = "INH";
    public $dimwidth = "";
    public $dimwidthcode = "INH";
    // end of required in SAP method, but not available in interface
    public $material = "";
    public $hardness = "";
    // added these as we have them in interface
    public $compound = "";
    public $temperature = "";
    public $coating = "";
    public $brand;
}
