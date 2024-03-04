<?php
class CRMAccount
{

    public $company; // mandatory field
    public $firstname; // mandatory field
    public $lastname; // mandatory field
    public $email; //mandatory field
    public $mobile; //mandatory field
    public $jobfunction; //mandatory field (Text field)
    public $vatnumber;
    public $department;
    /*
    Purchasing Dept.    0001
    Sales Dept. 0002
    Administration Dept.    0003
    QA Assurance Dept.  0005
    Secretary's Office  0006
    Financial Dept. 0007
    Legal Dept. 0008
    R&D Dept.   0018
    Product Dev Dept.   0019
    Executive Board Z020
    Packaging Dev Dept. Z021
    Production Dept.    Z022
    Quality Control Dept    Z023
    Logistics Dept. Z024
    Operations Dept.    Z025
    Advanced Pur Dept.  Z026
    Consulting Dept.    Z027
    IT Dept.    Z28
    Marketing Dept. Z29
    Customer Ser Dept.  Z30
    Audit Dept. Z31
    HR Dept.    Z32
    Engineering Z33
    Project Management  Z34
    Laboratory  Z35
    Procurement Z36
    Supply Chain Dept.  ZSC
    */
    public $address;
    public $city;
    public $state;
    public $country;
    public $postalcode;
}
