<?php
use GuzzleHttp\Client;

class CRMContact
{
    public $name = "";
    public $company = "";
    public $firstname = "";
    public $lastname = "";
    public $phone = "";
    public $email = "";
    public $mobile = "";
    public $contactid = "";
    public $accountid = "";
    public $sapAccountId = "";
    public $function = "";
    public $jobtitle = "";
    public $address = "";
    public $city = "";
    public $country = "";
    public $postalcode = "";

    public function __construct(string $email = "")
    {
        $this->email = $email;
    }

    public function getFirstName()
    {
        return $this->firstname == "" ? $this->firstname = substr($this->email, 0, strpos($this->email, '@')) : $this->firstname;
    }

    public function getLastName()
    {
        return $this->lastname == "" ? "N/A" : $this->lastname;
    }

    public function toArray()
    {
        $contact = [
            "Name" => $this->name,       // Mandatory - maybe this should be set to Keep_Me_Informed_2ndFeb2023
            "Company" => $this->company,// Mandatory – Contacts company name // New Acc KPI
            "ContactFirstName" => $this->firstname,  // Mandatory
            "ContactLastName" => $this->lastname,  // Mandatory
            "ContactPhone" => $this->phone,
            "ContactMobile" => $this->mobile,
            "ContactEMail" => $this->email,
        ];
        if ($this->contactid != "") {
            $contact['ContactID'] = $this->contactid;
        }
        if ($this->accountid != null) {
            $contact["AccountPartyID"] = $this->accountid;
        }
        return $contact;
    }

    public function toJSON()
    {
        return json_encode($this->toArray());
    }

    public function setDefault()
    {
        $this->name = "Contact test";
        $this->company = "Infolabix test company";
        $this->firstname = "José";
        $this->lastname = "Martins";
        $this->phone = "912345678";
        $this->email = "jose.martins@infolabix.com";
        $this->mobile = "923456781";
    }

    public function fillContactFromCRMContactObject($contact)
    {
        if ($contact != []) {
            $this->firstname = $contact->FirstName;
            $this->lastname = $contact->LastName;
            $this->name = $contact->Name;
            $this->phone = $contact->Phone;
            $this->mobile = $contact->Mobile;
            $this->email = $contact->Email;
            $this->company = $contact->AccountFormattedName;
            $this->contactid = $contact->ContactID;
            $this->accountid = $contact->AccountID;
            $this->sapAccountId = $contact->ExternalID;
        }
    }
}

class CRMSalesQuote
{
    public function __construct(CRMContact $crmcontact, CRMQuoteProduct $product)
    {
        $this->changeContact($crmcontact);
        $this->product = $product;
    }

    /**
     * Define contact and updates quote lead contact details
     */
    public function changeContact(CRMContact $crmcontact)
    {
        $this->crmcontact = $crmcontact;
        $this->name = "Sales Quote_" . date('d/m/Y') . "_" . substr($crmcontact->email, strpos($crmcontact->email, '@') + 1);
        $this->company = $crmcontact->company ?? substr($crmcontact->email, strpos($crmcontact->email, '@') + 1);
        $this->contactfirstname = substr($crmcontact->email, 0, strpos($crmcontact->email, '@'));
        $this->contactlastname = $crmcontact->lastname ?? "N/A";
        $this->email = $crmcontact->email;
        $this->mobile = $crmcontact->mobile;
        $this->contactfunction = $crmcontact->function;
        $this->contactjobtitle = $crmcontact->jobtitle;
        $this->contactaddress = $crmcontact->address;
        $this->contactcity = $crmcontact->city;
        $this->contactcountry = $crmcontact->country;
        $this->contactpostalcode = $crmcontact->postalcode;
    }

    protected string $name = "";
    protected string $company = "";
    protected string $contactfirstname = "";
    protected string $contactlastname = "";
    protected string $contactmobile = "";
    protected string $contactemail = "";
    protected string $contactfunction = "";
    protected string $contactjobtitle = "";
    protected string $contactaddress = "";
    protected string $contactcity = "";
    protected string $contactcountry = "";
    protected string $contactpostalcode = "";
    protected ?CRMContact $crmcontact = null;
    protected ?CRMQuoteProduct $product = null;

    public function getName()
    {
        return $this->name;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getContactFirstName()
    {
        return $this->contactfirstname;
    }

    public function getContactLastName()
    {
        return $this->contactlastname;
    }

    public function getContactMobile()
    {
        return $this->contactmobile;
    }

    public function getContactEmail()
    {
        return $this->contactemail;
    }

    public function getContactFunction()
    {
        return $this->contactfunction;
    }

    public function getContactJobTitle()
    {
        return $this->contactjobtitle;
    }

    public function getContactAddress()
    {
        return $this->contactaddress;
    }

    public function getContactCity()
    {
        return $this->contactcity;
    }

    public function getContactCountry()
    {
        return $this->contactcountry;
    }

    public function getContactPostalcode()
    {
        return $this->contactpostalcode;
    }

    public function getProduct(): CRMQuoteProduct
    {
        return $this->product;
    }

    public function getContact(): CRMContact
    {
        return $this->crmcontact;
    }

}

class CRMQuoteProduct
{
    public $quantity = "";
    public $quantitycode = "T3";  // not available in interface defaulting to 1000pc
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
}

class CRMLead
{

    public function createContactLeadBody(CRMContact $crmcontact, $comments, $prodofinterest)
    {
        // define contant fields
        $contact = [];

        if ($crmcontact->accountid != "") {
            $contact["AccountPartyID"] = $crmcontact->accountid;
        }

        $contact["Name"] = "Contact Us_" . date('d/m/Y') . "_" . substr($crmcontact->email, strpos($crmcontact->email, '@') + 1);

        $crmcontact->name;
        $contact["LeadLifecycle_KUT"] = "151"; // Sales quote lead
        $contact["CompanySectorL1_KUT"] = "121"; // Sales quote lead


        $contact["Company"] = $crmcontact->company;
        $contact["ContactFirstName"] = $crmcontact->getFirstName();
        $contact["ContactLastName"] = $crmcontact->getLastName();
        $contact["ContactEMail"] = $crmcontact->email;
        $contact["ContactMobile"] = $crmcontact->mobile;

        $contact["BusinessPartnerRelationshipBusinessPartnerFunctionTypeCode"] = $crmcontact->jobtitle;


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

        $rfq["Quantity1Content_KUT"] = $product->quantity ?? "N/A";
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
        $rfq["IDContent_KUT"] = $product->dimid ?? $product->dimensions ?? "N/A";
        $rfq["IDUnitCode_KUT"] = $product->dimidcode ?? "N/A";
        $rfq["ODContent_KUT"] = $product->dimod ?? "N/A";
        $rfq["ODUnitCode_KUT"] = $product->dimodcode ?? "N/A";
        $rfq["WidthContent_KUT"] = $product->dimwidth ?? "N/A";
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

        //$rfq["EndDate"]=$product->requiredby??"N/A";

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
            switch (str_tolower($brand)) {
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
                array_push($commitems, $this->getKMIComunicationItem($item, $option));
            }
            $lead["LeadMarketingPermissionChannelPermission"] = $commitems;
            // add subscription options
            $options = [];
            foreach ($listoptions as $item => $option) {
                array_push($options, $this->getOptionItem($item, $option));
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
                }
                array_push($lst, $option);
            }
        }
        return implode(",", $lst);
    }

    private function getKMIComunicationSubscriptionsRegisteredAccount($options)
    {
        $lst = [];
        foreach ($options as $optionitem => $sub) {
            if ($sub) {
                $optionitem = strtolower($optionitem);
                $option = "";
                switch ($optionitem) {
                    case "offers":
                        $option = "001";
                        break;
                    case "updates":
                        $option = "002";
                        break;
                    case "newsletters":
                        $option = "003";
                        break;
                    case "events":
                        $option = "004";
                        break;
                    case "surveys":
                        $option = "005";
                        break;
                    case "announcements":
                        $option = "Z01";
                        break;
                    case "blog":
                        $option = "Z02";
                        break;
                    case "news":
                        $option = "Z03";
                        break;
                    case "reports":
                        $option = "Z04";
                        break;
                    case "webcasts":
                        $option = "Z05";
                        break;
                    case "webinars":
                        $option = "Z06";
                        break;
                }
                array_push($lst, $option);
            }
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
    private function getOptionItem($option, $subscribe)
    {
        $option = strtolower($option);
        switch ($option) {
            case "offers":
                $option = "001";
                break;
            case "updates":
                $option = "002";
                break;
            case "newsletters":
                $option = "003";
                break;
            case "events":
                $option = "004";
                break;
            case "surveys":
                $option = "005";
                break;
            case "announcements":
                $option = "Z01";
                break;
            case "blog":
                $option = "Z02";
                break;
            case "news":
                $option = "Z03";
                break;
            case "reports":
                $option = "Z04";
                break;
            case "webcasts":
                $option = "Z05";
                break;
            case "webinars":
                $option = "Z06";
                break;
        }
        $item = ["CommunicationTypeCode" => $option, "SubscribedIndicator" => $subscribe];
        return $item;
    }
}

class CRMController
{
    private $baseURL = "https://my336469.crm.ondemand.com/sap/c4c/odata/v1/c4codataapi/";

    /**
     * Encode Credentials to Base64
     */
    protected function encodeCredentials()
    {
        return base64_encode('B2B_INT_USER:Datwyler@123456789');
    }

    /***
     * Gets X-CSRF token
     */
    private function GetXCSRFToken()
    {
        $resp = null;
        try {
            $client = new Client(); //GuzzleHttp\Client
            $url = $this->baseURL;// /\$metadata
            $credentials = $this->encodeCredentials();
            $response = $client->request('GET', $url, [
                'headers' => [
                    'authorization' => 'Basic ' . $credentials,
                    "Content-Type" => "application/json",
                    'x-csrf-token' => 'fetch',
                ],
            ]);
            $resp = $response->getHeaders()['x-csrf-token'][0];
            $lst = [];
            array_push($lst, $resp);
            array_push($lst, $response->getHeaders()['Set-Cookie'][0] . ';' . $response->getHeaders()['Set-Cookie'][1]);
        } catch (Exception $e) {
            return null;
        }
        return $lst;
    }

    private function createPostHeader($token_cookie)
    {
        $credentials = $this->encodeCredentials();
        $headers = [
            'content-type' => 'application/json',
            'authorization' => 'Basic ' . $credentials,
            'X-CSRF-Token' => $token_cookie[0], // token
            'Cookie' => $token_cookie[1], // cookie
            'Accept' => 'application/json',
        ];
        return $headers;
    }

    private function createGetHeader()
    {
        $credentials = $this->encodeCredentials();
        $header = [
            'authorization' => 'Basic ' . $credentials,
        ];
        return $header;
    }

    private function getAccount($accountid)
    {
        $client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "CorporateAccountCollection";
        $url = $url . '?$filter=AccountID eq \'' . $accountid . '\'';
        $url = $url . '&$format=json';
        $response = $client->request('GET', $url, [
            'headers' => $this->createGetHeader(),
        ]);
        $str = json_decode($response->getBody()->read($response->getBody()->getSize()));
        if (count($str->d->results) > 0)
            return $str->d->results[0];
        return [];
    }

    private function getContactByEmail($email)
    {
        $client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "ContactCollection";
        $url = $url . '?$filter=Email eq \'' . $email . '\'';
        $url = $url . '&$format=json';
        $response = $client->request('GET', $url, ['headers' => $this->createGetHeader()]);
        $str = json_decode($response->getBody()->read($response->getBody()->getSize()));
        if (count($str->d->results) > 0)
            return $str->d->results[0];
        return $str->d->results;
    }

    private function getSalesQuoteCollectionById($id)
    {
        $client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "SalesQuoteCollection";
        $url = $url . '?$filter=ID eq \'' . $id . '\'';
        $url = $url . '&$format=json';
        $response = $client->request('GET', $url, [
            'headers' => $this->createGetHeader(),
        ]);
        $str = json_decode($response->getBody()->read($response->getBody()->getSize()));
        return $str;
    }

    private function getContactCollection($accountid)
    {
        $client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "ContactCollection"; //LeadCollection
        $url = $url . '?$filter=Email eq \'' . $email . '\'';
        $url = $url . '&$format=json';
        $response = $client->request('GET', $url, [
            'headers' => $this->createGetHeader(),
        ]);
        $str = json_decode($response->getBody()->read($response->getBody()->getSize()));
        return $str;
    }

    private function getSalesQuoteCollectionByBuyerId($buyerid)
    {
        $client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "SalesQuoteCollection";
        $url = $url . '?$filter=BuyerPartyID eq \'' . $buyerid . '\'';
        $url = $url . '&$format=json';
        $response = $client->request('GET', $url, [
            'headers' => $this->createGetHeader(),
        ]);
        $str = json_decode($response->getBody()->read($response->getBody()->getSize()));
        return $str;
    }

    /***
     * Return lead with provided id or list of all leads
     */
    private function getLeadCollection($leadid = null)
    {
        $client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "LeadCollection"; //LeadCollection
        if ($leadid != null) {
            $url = $url . '?$filter=ID eq \'' . $leadid . '\'';
            $url = $url . '&$format=json';
        } else {
            $url = $url . '?$format=json';
        }
        $response = $client->request('GET', $url, [
            'headers' => $this->createGetHeader(),
        ]);
        $str = json_decode($response->getBody()->read($response->getBody()->getSize()));
        return $str;
    }

    private function createAccountBody()
    {
        $body = json_encode([
            "RoleCode" => "BUP002",
            "LifeCycleStatusCode" => "2",
            "Name" => "Test Acc Creation ",
            "AdditionalName" => "JM test",
            "CountryCode" => "DE",
            "HouseNumber" => "123",
            "Street" => "Test Street 1",
            "City" => "Test City",
            "StreetPostalCode" => "123",
            "Phone" => "123456789",
            "Mobile" => "123456789",
            "Fax" => "123456789",
            "Email" => "jmtest@test.com",
            "WebSite" => "http://168.63.37.239/",
            "LanguageCode" => "EN",
            "OwnerID" => "46",
            "Business" => "141",
            "GeoRegion" => "N3"
        ]);
        return $body;
    }

    private function makePostRequest($url, $headers, $body)
    {
        $client = new Client();
        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'body' => $body,
        ]);

        $str = json_decode($response->getBody()->read($response->getBody()->getSize()));
        return $str;
    }

    private function createAccount($token)
    {
        $url = $this->baseURL . "CorporateAccountCollection";
        $headers = $this->createPostHeader($token);
        $body = $this->createAccountBody();

        return $this->makePostRequest($url, $headers, $body);
    }

    private function createSalesQuoteBodyXX($accountid)
    {
        $body = json_encode([
            "BuyerID" => $accountid,
            "Name" => "Test Sales Quote Creataion JM from code V1",
            "ProcessingTypeCode" => "ZGI",
            "BuyerPartyID" => $accountid,
            "ProductRecipientPartyID" => $accountid,
            "EmployeeResponsiblePartyID" => "8000000039",
            "SalesUnitPartyID" => "AU_6000",
            "SalesOrganisationID" => "AU_6000",
            "DistributionChannelCode" => "01",
            "RequestedFulfillmentStartDateTime" => now(),
            "TimeZoneCode" => "UTC",
            "CurrencyCode" => "USD",
            "DocumentLanguageCode" => "EN",
            "DeliveryPriorityCode" => "3",
            "ProbabilityPercent" => "25.00",
            "Marketsubsegment" => "381",
            "ProductionSite" => "SMX",
            "SalesOrg" => "SMX",
            "Segment_KUT" => "GI"
        ]);
        return $body;
    }

    private function createSalesQuoteXX($token, $accountid)
    {
        $url = $this->baseURL . "SalesQuoteCollection";
        $headers = $this->createPostHeader($token);
        $body = $this->createSalesQuoteBody($accountid);

        return $this->makePostRequest($url, $headers, $body);
    }

    private function createLeadWithoutAccountXX($email, $token = null)
    {
        if ($token == null) {
            $token = $this->GetXCSRFToken();
        }
        $url = $this->baseURL . "LeadCollection";
        $headers = $this->createPostHeader($token);
        $body = $this->createLeadBody($email);

        $res = $this->makePostRequest($url, $headers, $body);
        return $res->d->results;
    }


    protected function createLeadXX($crmcontact, $token = null)
    {
        if ($token == null) {
            $token = $this->GetXCSRFToken();
        }
        //get mandatory fields from contact
        $contact = $this->getContactByEmail($crmcontact->email);

        if ($contact == []) { // if contact does not exists, create lead without contact details
            //$account=$this->getAccount($accountid,$token);
            return $this->createLeadWithoutAccount($email);
        } else {
            //fetch contactid
            $contactid = $contact->ContactID;
            //fetch accountid
            $accountid = $contact->AccountID;
            $account = $this->getAccount($accountid, $token);
            $accountpartyid = $account->ExternalID;
            $body = $this->createLeadBody($email, $contactid, $accountpartyid);
            $option = $this->createKMIComunicationItem("tel", true);
        }

        $url = $this->baseURL . "LeadCollection";
        $headers = $this->createPostHeader($token);

        return $this->makePostRequest($url, $headers, $body);
    }

    /***
     * Create KMI Lead. CRMContact must have email defined
     */
    protected function createKMILead($crmcontact, $communicationoptions, $itemoptions, $token = null)
    {
        if ($token == null) {
            $token = $this->GetXCSRFToken();
        }
        //get mandatory fields from contact
        $contact = $this->getContactByEmail($crmcontact->email); // get contact from crm
        if (!empty($crmcontact)) {
            $crmcontact->fillContactFromCRMContactObject($contact); // fill the contact with SAP CRM data
        }

        $lead = new CRMLead();
        $body = $lead->createKMILeadBody($crmcontact, $communicationoptions, $itemoptions);
        $url = $this->baseURL . "LeadCollection";
        $headers = $this->createPostHeader($token);

        $lead = $this->makePostRequest($url, $headers, $body);
        return $lead->d->results;
    }

    /***
     * Create Contact Us Lead. CRMContact must have email defined
     * Fill the CRMcontact Object before requesting the lead
     */
    protected function createContactUsLead($crmcontact, $comments, $productofinterest, $token = null)
    {
        if ($token == null) {
            $token = $this->GetXCSRFToken();
        }
        //get mandatory fields from contact

        $lead = new CRMLead();
        $body = $lead->createContactLeadBody($crmcontact, $comments, $productofinterest);
        $url = $this->baseURL . "LeadCollection";
        $headers = $this->createPostHeader($token);

        $lead = $this->makePostRequest($url, $headers, $body);
        return $lead->d->results;
    }

    /***
     * Create KMI Lead. CRMContact must have email defined and company
     */
    protected function createSalesQuoteLead(CRMSalesQuote $crmsalesquote, $token = null)
    {
        if ($token == null) {
            $token = $this->GetXCSRFToken();
        }
        $lead = new CRMLead();
        $body = $lead->createSalesQuoteLeadBody($crmsalesquote);

        $url = $this->baseURL . "SalesQuoteCollection";
        $url = $this->baseURL . "LeadCollection";
        $headers = $this->createPostHeader($token);

        $lead = $this->makePostRequest($url, $headers, $body);
        return $lead->d->results;
    }

    public function testKMILeadCreation($email)
    {
        /** create contact object to use **/
        $crmcontact = new CRMContact($email);
        //$crmcontact->setDefault();
        /** end of create contact object to use **/

        $communicationoptions = ["tel" => true, "whatsapp" => false];
        $itemoptions = ["offers" => false, "announcements" => true, "news" => true];

        $lead = $this->createKMILead($crmcontact, $communicationoptions, $itemoptions, $token = null);
        return $lead;
    }

    public function testSalesQuoteLeadCreation($email)
    {
        /** create contact object to use **/
        $crmcontact = new CRMContact($email);
        $contact = $this->getContactByEmail($crmcontact->email); // get contact from crm
        if (!empty($contact)) {
            $crmcontact->fillContactFromCRMContactObject($contact); // fill the contact with SAP CRM data
        } else {
            $crmcontact->company = "Not provided"; // mandatory field
            $crmcontact->lastname = "Not provided"; // mandatory field
            // code...
        }
        $crmquoteproduct = new CRMQuoteProduct();

        $crmquoteproduct->quantity = "100"; //sample data
        $crmquoteproduct->quantitycode = "T3";  // use 1000pc by default
        $crmquoteproduct->application = "Chemical Resistant"; //options are: Chemical Resistant/Oil Resistant/Water and Steam Resistant
        $crmquoteproduct->requiredby = "next week"; // free text
        $diagram = null; // this is a file tbd
        $crmquoteproduct->partnumber = "xx05"; // free text
        $crmquoteproduct->comments = "These are the free text comments";  // free text
        $crmquoteproduct->material = "FLUOROCARBON RUBBER - FKM";
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
        $crmquoteproduct->hardness = ""; // 70
        $crmquoteproduct->product = "321"; // product of interest
        /*
        Description Internal Code
        Custom Molded Rubber Seals  141
        Rubber to Metal Bonded Seals    151
        Machined Thermoplastic  171
        None    311
        O-Ring  321
        Rubber to Plastic Bonded Seals  331
        Custom Machined Metal Parts 341
        Molded Resins   351
        Surface Production Equipment    361
        Wearable Sensors    371
        */
        $crmquoteproduct->dimensions = "0.10x0.5x0.15 mm";
        $crmquoteproduct->dimensionscode = "T3"; //1000pc
        // required in SAP method, but not available in interface
        $crmquoteproduct->dimid = "0.1";
        $crmquoteproduct->dimidcode = "INH";
        $crmquoteproduct->dimod = "0.5";
        $crmquoteproduct->dimodcode = "INH";
        $crmquoteproduct->dimwidth = "0.15";
        $crmquoteproduct->dimwidthcode = "INH";
        // end of required in SAP method, but not available in interface
        $crmquoteproduct->compound = "this is the compound";
        $crmquoteproduct->temperature = "this is temperature range";
        $crmquoteproduct->coating = "This is coating";

        $crmquote = new CRMSalesQuote($crmcontact, $crmquoteproduct);
        /** end of create contact object to use **/
        $lead = $this->createSalesQuoteLead($crmquote);
        return $lead;
    }

    public function testContactUsLead($email)
    {
        $crmcontact = new CRMContact($email);
        $contact = $this->getContactByEmail($crmcontact->email); // get contact from crm
        if (!empty($contact)) {
            $crmcontact->fillContactFromCRMContactObject($contact); // fill the contact with SAP CRM data
        } else {
            $crmcontact->company = "Not provided"; // mandatory field
            $crmcontact->lastname = "Not provided"; // mandatory field
            $crmcontact->mobile = '+351 912345678'; //mandatory field
            $crmcontact->jobtitle = '0001'; //mandatory field
            /*
            Ms. 0001
            Mr. 0002
            */
        }
        $comments = "these are test comments for lead";
        $productofinterest = "141";
        /*
        Description Internal Code
        Custom Molded Rubber Seals  141
        Rubber to Metal Bonded Seals    151
        Machined Thermoplastic  171
        None    311
        O-Ring  321
        Rubber to Plastic Bonded Seals  331
        Custom Machined Metal Parts 341
        Molded Resins   351
        Surface Production Equipment    361
        Wearable Sensors    371
        */

        return $this->createContactUsLead($crmcontact, $comments, $productofinterest);
    }

    public function CRMTester()
    {
        //$email='hliu@summitbiosciences.com';
        $email = 'jmartins@infolabix.com';

        //$lead=$this->testKMILeadCreation($email);
        //dd($lead);

        //$lead=$this->testSalesQuoteLeadCreation($email);
        //dd($lead);

        $lead = $this->testContactUsLead($email);
        dd($lead);

        //print_r($this->getAccountCollection($token,1000618));

        //$account=$this->createAccount($token);
        //$accountid=$account->d->results->AccountID; // id to use on sales quote
        $accountid = "1032212";

        //dd($this->getAccountCollection($token,$accountid));

        //$rfq=$this->createSalesQuote($token,$accountid);
        //$rfqid=$rfq->d->results->ID; // id from salesquote

        //$quote=$this->getSalesQuoteCollectionByID($token,$rfqid); //sales quote by id
        //$quotedetail=$quote->d->results;

        //$quotelist=$this->getSalesQuoteCollectionByBuyerID($token,$accountid);
        //$quotelistdetail=$quotelist->d->results;

        return 'OK ->' . $token;
    }
}
