<?php

class CRMLead
{
    public $leadid;
    public $leadtype;
    public $leadparentobjectid;
    public $status;
    public $accountid;
    public $contactid;

    public function loadLead($data)
    {
        try {
            $this->leadid = $data->ID;
            $this->accountid = $data->AccountPartyID;
            $this->leadparentobjectid = $data->ObjectID;
            $this->status = $data->UserStatusCodeText;
            $this->contactid = $data->ContactID;
        } catch (Exception $e) {
        }
    }

    public function get_mime_type_from_url($file_url)
    {
        // Use WordPress HTTP API to fetch the remote file
        $response = wp_remote_head($file_url);

        if (is_wp_error($response)) {
            return $response->get_error_message();
        } else {
            // Get headers from the response
            $headers = wp_remote_retrieve_headers($response);

            // Extract content type header
            $content_type = isset($headers['content-type']) ? $headers['content-type'] : '';

            return $content_type;
        }
    }


    public function createFileLeadBody($file_url)
    {
        $filebin = file_get_contents($file_url);
        $body = [];
        $body["CategoryCode"] = "2";  // Always 2
        $body["LeadID"] = $this->leadid;
        $body["MimeType"] = $this->get_mime_type_from_url($file_url);  // Changes based on type of file
        $body["Name"] = "Sales-Quote"; // mandatory
        $body["ParentObjectID"] = $this->leadparentobjectid;  // Same LeadObjectID from LeadCollection
        $body["TypeCode"] = "10001";  // Always 10001
        $body["Binary"] = base64_encode($filebin);

        return json_encode($body);
    }

    public function createAccountLeadBody(CRMAccount $account)
    {
        $body = [];
        $body["Name"] = "AccountCreation_" . date('d/m/Y') . "_" . $account->company;
        $body["Company"] = $account->company;// mandatory field
        $body["ContactFirstName"] = $account->firstname;
        $body["ContactLastName"] = $account->lastname;
        $body["ContactEMail"] = $account->email;
        $body["ContactFunctionalTitleName"] = $account->jobfunction;
        $body["BusinessPartnerRelationshipBusinessPartnerFunctionalAreaCode"] = $account->department;
        $body["ContactMobile"] = $account->mobile;
        $body["AccountPostalAddressElementsStreetName"] = $account->address;
        $body["AccountCity"] = $account->city;
        $body["AccountState"] = $account->state;
        $body["AccountCountry"] = $account->country;
        $body["AccountPostalAddressElementsStreetPostalCode"] = $account->postalcode;
        $body["TaxNumber_KUT"] = $account->vatnumber;
        //"OwnerPartyID": "8000000770", //  Not Mandatory if not added then creator B2BINT user become owner, 8000000820 //for TST, 8000000821 for Parco , 8000000822 for Double EE , 8000000823 for Olypian
        //$body["OwnerPartyID"]="8000000770";//FIXED VALUE
        $body["Business_KUT"] = "141";//FIXED VALUE
        $body["LeadLifecycle_KUT"] = "161";//FIXED VALUE -> New account Creation
        $body["LeadType_KUT"] = "105";//FIXED VALUE -> GI
        $body["Segment"] = "GI";//FIXED VALUE -> GI
        $body["OriginTypeCode"] = "Z38";//FIXED VALUE -> GI
        return json_encode($body);
    }

    public function createContactLeadBody(CRMContact $crmcontact, $comments, $prodofinterest)
    {
        // define contant fields
        $contact = [];

        if ($crmcontact->accountid != "") {
            $contact["AccountPartyID"] = $crmcontact->accountid;
        }

        $contact["Name"] = "Contact Us_" . date('d/m/Y') . "_" . substr($crmcontact->email, strpos($crmcontact->email, '@') + 1);

        $contact["LeadLifecycle_KUT"] = "141"; // Contact Us quote lead
        $contact["CompanySectorL1_KUT"] = "121"; // Contact Us quote lead


        $contact["Company"] = $crmcontact->company;
        $contact["ContactFirstName"] = $crmcontact->getFirstName();
        $contact["ContactLastName"] = $crmcontact->getLastName();
        $contact["ContactEMail"] = $crmcontact->email;
	$contact["ContactMobile"] = $crmcontact->mobile;
        $contact["BusinessPartnerRelationshipBusinessPartnerFunctionTypeCode"] = $crmcontact->jobfunction;

        $contact["ContactFunctionalTitleName"] = $crmcontact->jobtitle;
//ContactFormOfAddressCode"] = $crmcontact->jobtitle;


        $contact["Note"] = $comments;
        $contact["ProductofInterest_KUT"] = $prodofinterest;
        /*
        CHLOROPRENE RUBBER - CR (Neoprene™)
        ETHYLENE-PROPYLENE-DIENE RUBBER - EPDM
        FLUOROCARBON RUBBER - FKM
        FLUOROSILICONE - FVMQ
        HYDROGENATED NITRILE - HNBR
        NITRILE BUTADIENE RUBBER - NBR
        SILICONE RUBBER - VMQ
        TETRAFLUOROETHYLENE PROPYLENE - TFP (Aflas®)
        */


        return json_encode($contact);
    }

    public function createSalesQuoteLeadBody(CRMSalesQuote $crmsalesquote)
    {
        $product = $crmsalesquote->getProduct();
        // define contant fields
        $rfq = [];

        //$rfq["AccountPartyID"]= "1031830"; // what if it does not exists
        if ($crmsalesquote->getContact()->accountid != "") {
            $rfq["AccountPartyID"] = $crmsalesquote->getContact()->accountid;
        }


        $rfq["Name"] = $crmsalesquote->getName();
        //$rfq["SalesUnitPartyID"]= "AU_6000";
        //$rfq["SalesOrganisationID"]= "AU_6000";
        //$rfq["DistributionChannelCode"]= "01";

        //$rfq["RequestedFulfillmentStartDateTime"]= now();
        //$rfq["TimeZoneCode"]= "UTC";
        //$rfq["CurrencyCode"]= "USD";
        //$rfq["DocumentLanguageCode"]= "EN";
        //$rfq["DeliveryPriorityCode"]= "3";
        //$rfq["ProbabilityPercent"]= "25.00";
        //$rfq["Marketsubsegment"]= "381";
        //$rfq["ProductionSite"]= "SMX";
        //$rfq["SalesOrg"]= "SMX";
        //$rfq["Segment_KUT"]= "GI";
        //$rfq["LeadType_KUT"]= "105";
        $rfq["LeadLifecycle_KUT"] = "151"; // Sales quote lead
        $rfq["CompanySectorL1_KUT"] = "171"; // Sales quote lead


        $rfq["Company"] = $crmsalesquote->getCompany();
        $rfq["ContactFirstName"] = $crmsalesquote->getContactFirstName();
        $rfq["ContactLastName"] = $crmsalesquote->getContactLastName();
        $rfq["ContactEMail"] = $crmsalesquote->getContactEmail();
        $rfq["ContactMobile"] = $crmsalesquote->getContactMobile();

        $rfq["ContactFunctionalTitleName"] = $crmsalesquote->getContactFunction();
        $rfq["BusinessPartnerRelationshipBusinessPartnerFunctionTypeCode"] = $crmsalesquote->getContactJobtitle();

        $rfq["AccountPostalAddressElementsStreetName"] = $crmsalesquote->getContactAddress();
        $rfq["AccountCity"] = $crmsalesquote->getContactCity();
        //$rfq["AccountState"]=""; //N/available
        $rfq["AccountCountry"] = $crmsalesquote->getContactCountry();
        $rfq["AccountPostalAddressElementsStreetPostalCode"] = $crmsalesquote->getContactPostalcode();

        $rfq["Quantity1Content_KUT"] = $product->quantity ?? "0";
        $rfq["Quantity1UnitCode_KUT"] = $product->quantitycode ?? "N/A";

        $rfq["DesiredApplication_KUT"] = $product->application ?? "N/A";
        /*
        Chemical Resistant
        Oil Resistant
        Water and Steam Resistan
        */

        /**
         * Diagram to check in doc
         */
        $rfq["PartNumber_KUT"] = $product->partnumber ?? "N/A";
        $rfq["Note"] = $product->comments ?? "N/A";  // comments
        $rfq["ProductofInterest_KUT"] = $product->product ?? "N/A";


        $rfq["Coating3_KUT"] = $product->coating;
        $rfq["Compound1_KUT"] = $product->compound;
        $rfq["Dimensions_KUT"] = $product->dimensions;
        $rfq["Temperature_KUT"] = $product->temperature;

        //dimensions
        $rfq["IDContent_KUT"] = $product->dimid ?? $product->dimensions ?? "0";
        $rfq["IDUnitCode_KUT"] = $product->dimidcode ?? "N/A";
        $rfq["ODContent_KUT"] = $product->dimod ?? "0";
        $rfq["ODUnitCode_KUT"] = $product->dimodcode ?? "N/A";
        $rfq["WidthContent_KUT"] = $product->dimwidth ?? "0";
        $rfq["WidthUnitCode_KUT"] = $product->dimwidthcode ?? "N/A";
        $rfq["Material_KUT"] = $product->material ?? "N/A";
        /*
        CHLOROPRENE RUBBER - CR (Neoprene™)
        ETHYLENE-PROPYLENE-DIENE RUBBER - EPDM
        FLUOROCARBON RUBBER - FKM
        FLUOROSILICONE - FVMQ
        HYDROGENATED NITRILE - HNBR
        NITRILE BUTADIENE RUBBER - NBR
        SILICONE RUBBER - VMQ
        TETRAFLUOROETHYLENE PROPYLENE - TFP (Aflas®)
        */

        $rfq["Hardness_KUT"] = $product->hardness ?? "N/A";

        //$rfq["OwnerPartyID"] = "8000000770";  // no brand defined by default
        $rfq["OwnerPartyID"] = "";  // no brand defined by default
        if ($crmsalesquote->getBrand() != "") {
            switch (strtolower($crmsalesquote->getBrand())) {
                case "tst":
                    $rfq["OwnerPartyID"] = "8000000820";  // TST
                    break;
                case "parco":
                    $rfq["OwnerPartyID"] = "8000000821";  // PARCO
                    break;
                case "doublee":
                    $rfq["OwnerPartyID"] = "8000000822";  // Double E
                    break;
                case "olympian":
                    $rfq["OwnerPartyID"] = "8000000823";  // Olympian
                    break;
            }
        }

        return json_encode($rfq);
    }

    /***
     * Create lead Marketing body based on contact details
     */
    public function createMarketingLeadBody($crmcontact, $brand = "")
    {
        $lead = $contactcrm->toArray();
        $lead["LeadLifecycle_KUT"] = "111";    // Marketing Qualified Lead
        $lead["LeadType_KUT"] = "105";         // GI Lead – always send this data
        $lead["Segment"] = "GI";               // GI Business – always send this data
        $lead["OriginTypeCode"] = "Z11";        // Website – always send this data
        $lead["OwnerPartyID"] = "8000000770";  // no brand defined by default
        if ($brand != "") {
            switch (strtolower($brand)) {
                case "tst":
                    $lead["OwnerPartyID"] = "8000000820";  // TST
                    break;
                case "parco":
                    $lead["OwnerPartyID"] = "8000000821";  // PARCO
                    break;
                case "doublee":
                    $lead["OwnerPartyID"] = "8000000822";  // Double E
                    break;
                case "olympian":
                    $lead["OwnerPartyID"] = "8000000823";  // Olympian
                    break;
            }
        }
        $body = json_encode($lead);
        return $body;
    }

    /***
     * Create KMI lead body based on contact details, and list of communications and subscriptions
     */
    public function createKMILeadBody($crmcontact, $listcomm, $listoptions)
    {
        //default lead data
        $lead = [
            "Name" => "KMI_" . date('d/m/Y') . "_" . substr($crmcontact->email, strpos($crmcontact->email, '@') + 1),
            "ContactAllowedCode" => "1", // Always send this for KMI Scenario
            "OrganisationAccountContactAllowedCode" => "1", // Always send this for KMI Scenario
            "ContactMobile" => $crmcontact->mobile == "" ? $crmcontact->phone : $crmcontact->mobile,
            "Business_KUT" => "141",    // Always send this 141 is GI
            "LeadLifecycle_KUT" => "131",  //  Always send this for KMI Scenario
            "LeadType_KUT" => "105",  //   Always send this 105  is GI
            "Segment" => "GI",  // Always send
            "OriginTypeCode" => "Z38",  // Always send this Z38 is GI Website
            //"ProductofInterest_KUT"=> "321,351,371", we don't have this in our interface
        ];
        if ($crmcontact->contactid != "") { // specific fields for existing contacts
            $lead["ContactID"] = $crmcontact->contactid;
            $lead["AccountPartyID"] = $crmcontact->accountid;
            //if contact has account create it as html data....kinda stupid
            $lead["ContactDataToBeUpdated_KUT"] = $this->getRichTextCommOptions($crmcontact, $listcomm, $listoptions);

            $lead["ContactPreference_KUT"] = $this->getKMIComunicationItemsRegisteredAccount($listcomm); // set communications
            $lead["CommunicationType_KUT"] = $this->getKMIComunicationSubscriptionsRegisteredAccount($listoptions); // set Subscriptions
        } else {
            $lead["Company"] = substr($crmcontact->email, strpos($crmcontact->email, '@') + 1);
            $lead["ContactFirstName"] = substr($crmcontact->email, 0, strpos($crmcontact->email, '@'));
            $lead["ContactLastName"] = "N/A";
            $lead["ContactEMail"] = $crmcontact->email;
            //add kmi comunication items
            $commitems = [];
            foreach ($listcomm as $item => $option) {
                $commitems[] = $this->getKMIComunicationItem($item, $option);
            }
            $lead["LeadMarketingPermissionChannelPermission"] = $commitems;
            // add subscription options
            $options = [];
            foreach ($listoptions as $item) {
                $options[] = $this->getOptionItem($item, true);
            }
            $lead["LeadMarketingPermissionCommTypePermission"] = $options;
        }

        $body = json_encode($lead);
        return $body;
    }

    private function getRichTextCommOptions($crmcontact) //,$listcomm,$listoptions)
    {
        $basestr = "<div>";
        $basestr .= "Contact Mobile: " . $crmcontact->mobile . "<br>";
        $basestr .= "Contact Email: " . $crmcontact->email . "<br><br>";
        /* changed 20240226 dropped html communication options
        //-- Change Marketingf permissions
        $basestr.="Contact Preference:<br>";

        foreach($listcomm as $item=>$option){
            $basestr.=$item." : ".$option?"Yes":"No"."<br>";
        }
        $basestr.="<br>";
        foreach($listoptions as $item=>$option)
        {
            $basestr.=$item." : ".$option?"Yes":"No"."<br>";
        }
        */
        $basestr .= "</div>";
        return $basestr;
    }

    private function getKMIComunicationItemsRegisteredAccount($listcomm)
    {
        $lst = [];
        foreach ($listcomm as $optionitem => $sub) {
            if ($sub == true) {
                $optionitem = strtolower($optionitem);
                $option = "";
                switch ($optionitem) {
                    case "fax":
                        $option = "FAX";
                        break;
                    case "e-mail":
                        $option = "INT";
                        break;
                    case "sms":
                        $option = "SMS";
                        break;
                    case "tel":
                        $option = "TEL";
                        break;
                    case "whatsapp":
                        $option = "ZWA";
                        break;
		    default:
			$option="INT";
			break;
                }
                array_push($lst, $option);
            }
        }
        return implode(",", $lst);
    }

    private function getKMIComunicationSubscriptionsRegisteredAccount($options)
    {
        $lst = [];
        foreach ($options as $key) {
            $option = "";
            switch ($key) {
                case "n_events":
                    $option = "Z08";
                    break;
                case "n_announcements":
                    $option = "Z01";
                    break;
                case "n_blog":
                    $option = "Z02";
                    break;
                case "n_news":
                    $option = "Z03";
                    break;
                case "n_reports":
                    $option = "Z04";
                    break;
                case "n_webcasts":
                    $option = "Z05";
                    break;
                case "n_webinars":
                    $option = "Z06";
                    break;
                case "b_blog":
                    $option = "Z13";
                    break;
                case "b_events":
                    $option = "Z09";
                    break;
                case "b_announcements":
                    $option = "Z14";
                    break;
                case "b_news":
                    $option = "Z10";
                    break;
                case "b_reports":
                    $option = "Z11";
                    break;
                case "b_webcasts":
                    $option = "Z12";
                    break;

            }
            $lst[] = $option;
        }
        return implode(",", $lst);
    }

    /***
     * Option can be FAX,E-MAIL,SMS,TELEPHONE,WHATSAPP
     * Subscribe is true or false
     */
    private function getKMIComunicationItem($option, $subscribe)
    {
        $option = strtolower($option);
        switch ($option) {
            case "fax":
                $option = "FAX";
                break;
            case "e-mail":
                $option = "INT";
                break;
            case "sms":
                $option = "SMS";
                break;
            case "tel":
                $option = "TEL";
                break;
            case "whatsapp":
                $option = "ZWA";
                break;
        }
        $item = ["CommunicationMediumTypeCode" => $option, "MarketingPermissionCode" => ((int)$subscribe) == 0 ? "2" : "1"];
        return $item;
    }

    /**
     * Options can be Offers/Updates/Newslatters/events/surveys/announcements/blog/news/reports/webcasts/webinars
     * Subscribe is true or false
     */
    private function getOptionItem($option)
    {
        $option = strtolower($option);
        switch ($option) {
            case "n_events":
                $option = "Z08";
                break;
            case "n_announcements":
                $option = "Z01";
                break;
            case "n_blog":
                $option = "Z02";
                break;
            case "n_news":
                $option = "Z03";
                break;
            case "n_reports":
                $option = "Z04";
                break;
            case "n_webcasts":
                $option = "Z05";
                break;
            case "n_webinars":
                $option = "Z06";
                break;
            case "b_blog":
                $option = "Z13";
                break;
            case "b_events":
                $option = "Z09";
                break;
            case "b_announcements":
                $option = "Z14";
                break;
            case "b_news":
                $option = "Z10";
                break;
            case "b_reports":
                $option = "Z11";
                break;
            case "b_webcasts":
                $option = "Z12";
                break;
        }
        $item = ["CommunicationTypeCode" => $option, "SubscribedIndicator" => true];
        return $item;
    }
}
