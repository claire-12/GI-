<?php

class CRMController
{
    private string $baseURL = "https://my336469.crm.ondemand.com/sap/c4c/odata/v1/c4codataapi/";
    private string $username = "B2B_INT_USER";
    private string $password = "Datwyler@123456789";

    /**
     * Encode Credentials to Base64
     */
    protected function encodeCredentials()
    {
        //return base64_encode('B2B_INT_USER:Datwyler@123456789');
        return base64_encode("$this->username:$this->password");
    }

    /***
     * Gets X-CSRF token
     */
    private function GetXCSRFToken()
    {
        try {
            $url = $this->baseURL;// /\$metadata
            $credentials = $this->encodeCredentials();

            $response = wp_remote_get($url, array(
                'headers' => [
                    'authorization' => 'Basic ' . $credentials,
                    "Content-Type" => "application/json",
                    'x-csrf-token' => 'fetch',
                ],
            ));

            $token = wp_remote_retrieve_header($response, 'x-csrf-token');
            $cookies = wp_remote_retrieve_header($response, 'Set-Cookie');

            $lst = [];
            $lst[] = $token;
            $lst[] = $cookies[0] . ';' . $cookies[1];
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

    private function makePostRequest($url, $headers, $body)
    {
        $response = wp_remote_post($url, array(
            'headers' => $headers,
            'body' => $body
        ));

        //echo '<pre>';var_dump(json_decode($body, true),$response);exit();
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            $result = array('error' => $error_message);
        } else {
            $result = json_decode(wp_remote_retrieve_body($response), true);
        }

        return $result;
    }

    private function makeGetRequest($url)
    {
        $response = wp_remote_get($url, array(
            'headers' => $this->createGetHeader()
        ));

        if (is_wp_error($response)) {
            return [];
        } else {
            // Get the response body
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body);
            if (count($data->d->results) > 0)
                return $data->d->results[0];
        }
        return [];
    }

    private function getAccount($accountid)
    {
        // $client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "CorporateAccountCollection";
        $url = $url . '?$filter=AccountID eq \'' . $accountid . '\'';
        $url = $url . '&$format=json';
        /*$response = $client->request('GET', $url, [
            'headers' => $this->createGetHeader(),
        ]);
        $str = json_decode($response->getBody()->read($response->getBody()->getSize()));
        if (count($str->d->results) > 0)
            return $str->d->results[0];
        return [];*/
        $response = $this->makeGetRequest($url);

        return $response;
    }

    private function getContactByEmail($email)
    {
        //$client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "ContactCollection";
        $url = $url . '?$filter=Email eq \'' . $email . '\'';
        $url = $url . '&$format=json';
        /*$response = $client->request('GET', $url, ['headers' => $this->createGetHeader()]);
        $str = json_decode($response->getBody()->read($response->getBody()->getSize()));
        if (count($str->d->results) > 0)
            return $str->d->results[0];
        return $str->d->results;*/
        $response = $this->makeGetRequest($url);

        return $response;
    }

    private function getSalesQuoteCollectionById($id)
    {
        //$client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "SalesQuoteCollection";
        $url = $url . '?$filter=ID eq \'' . $id . '\'';
        $url = $url . '&$format=json';

        $response = $this->makeGetRequest($url);

        return $response;
    }

    private function getContactCollection($accountid)
    {
        //$client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "ContactCollection"; //LeadCollection
        $url = $url . '?$filter=Email eq \'' . $email . '\'';
        $url = $url . '&$format=json';

        $response = $this->makeGetRequest($url);

        return $response;
    }

    private function getSalesQuoteCollectionByBuyerId($buyerid)
    {
        //$client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "SalesQuoteCollection";
        $url = $url . '?$filter=BuyerPartyID eq \'' . $buyerid . '\'';
        $url = $url . '&$format=json';

        $response = $this->makeGetRequest($url);

        return $response;
    }

    /***
     * Return lead with provided id or list of all leads
     */
    private function getLeadCollection($leadid = null)
    {
        //$client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "LeadCollection"; //LeadCollection
        if ($leadid != null) {
            $url = $url . '?$filter=ID eq \'' . $leadid . '\'';
            $url = $url . '&$format=json';
        } else {
            $url = $url . '?$format=json';
        }

        $response = $this->makeGetRequest($url);

        return $response;
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
        $body = $lead->createKMILeadBody($crmcontact, $communicationoptions, $itemoptions);;
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

        $results = $this->makePostRequest($url, $headers, $body);
        return $results;
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

        $res = $this->makePostRequest($url, $headers, $body);
        $lead->loadLead($res->d->results);
        if ($lead->leadid > 0) {
            if ($crmsalesquote->getFilePath() != null) {
                try {
                    $this->addFileToLead($lead, $crmsalesquote->getFilePath(), $token);
                } catch (Exception $ex) {
                    echo 'Caught exception: ', $e->getMessage(), "\n";
                }
            }
        }
        //return $lead->d->results;
        return $lead;
    }

    /***
     * Adds File to lead
     */
    protected function addFileToLead(CRMLead $lead, $filepath, $token = null)
    {
        if ($token == null) {
            $token = $this->GetXCSRFToken();
        }
        $body = $lead->createFileLeadBody($filepath);

        $url = $this->baseURL . "LeadAttachmentFolderCollection";
        $headers = $this->createPostHeader($token);

        $res = $this->makePostRequest($url, $headers, $body);
        return $res->d->results;
        $resultlead = new CRMLead();
        //$resultlead->loadLead($res->d->results);
        return $resultlead;
    }

    /***
     * Create Account Lead. CRMContact must have email defined
     * Fill the CRMcontact Object before requesting the lead
     */
    protected function createAccountLead(CRMAccount $crmaccount, $token = null)
    {
        if ($token == null) {
            $token = $this->GetXCSRFToken();
        }
        //get mandatory fields from contact

        $lead = new CRMLead();
        $body = $lead->createAccountLeadBody($crmaccount);
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

        $communicationoptions = ["tel" => true, "whatsapp" => false, "sms" => true];
        $itemoptions = ["offers" => false, "announcements" => true, "news" => true, "webinars" => true];

        $lead = $this->createKMILead($crmcontact, $communicationoptions, $itemoptions, null);
        return $lead;
    }

    public function testSalesQuoteLeadCreation($email, $file = null)
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
        $crmquoteproduct->brand = "tst";

        //$file="C://xampp8.0/htdocs/pim-gi/public/storage/productthumbs/ptype_1706791744.png";

        $crmquote = new CRMSalesQuote($crmcontact, $crmquoteproduct, $file);
        /** end of create contact object to use **/
        $lead = $this->createSalesQuoteLead($crmquote);
        return $lead;
    }

    public function processContactUsSubmit($contactForm)
    {
        $crmcontact = new CRMContact($contactForm['email']);
        $contact = $this->getContactByEmail($crmcontact->email);
        if (!empty($contact)) {
            $crmcontact->fillContactFromCRMContactObject($contact);
        } else {
            $crmcontact->company = $contactForm['company'];
            $crmcontact->lastname = $contactForm['lastname'];
            $crmcontact->mobile = $contactForm['mobile'];
            $crmcontact->jobtitle = $contactForm['jobtitle'];
        }

        return $this->createContactUsLead($crmcontact, $contactForm['message'], $contactForm['product']);
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

    public function testAccountCreationLead($email)
    {
        $account = new CRMAccount();
        $crmcontact = new CRMContact($email);
        $contact = $this->getContactByEmail($crmcontact->email); // get contact from crm
        if (!empty($contact)) {
            $crmcontact->fillContactFromCRMContactObject($contact); // fill the contact with SAP CRM data
            return "account already exists SAP account:";
        } else {
            $account->company = "This is a test company from Infolabix"; // mandatory field
            $account->firstname = "John"; // mandatory field
            $account->lastname = "Doe"; // mandatory field
            $account->email = 'john.doe@infolablix.com'; //mandatory field
            $account->mobile = '+351 912345678'; //mandatory field
            $account->jobfunction = 'Engineer'; //mandatory field (Text field)
            $account->department = '0001'; //mandatory field from the list below
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

            $account->address = 'Av. Something'; //mandatory field (Text Field)
            $account->city = 'Dream City'; //mandatory field
            $account->state = ''; // State ISO code
            $account->country = 'PT'; //Country ISO Code
            $account->postalcode = '1000-100'; // Postal Code
        }
        return $this->createAccountLead($account);
    }

    public function testAddFileToSalesQuoteLead(CRMLead $lead, $filepath)
    {
        return $this->addFileToLead($lead, $filepath);
    }

    public function CRMTester()
    {
        //$email='hliu@summitbiosciences.com';
        //$email='jmartins123@infolabix.com';
        $email = 'john.doe@infolablix.com';

        //$lead=$this->testKMILeadCreation($email);
        //dd($lead);
        $file = "C://xampp8.0/htdocs/pim-gi/public/storage/productthumbs/ptype_1706791744.png";
        $lead = $this->testSalesQuoteLeadCreation($email, $file);
        //dd($lead);

        //$lead=$this->testContactUsLead($email);
        //dd($lead);

        //$lead=$this->testAccountCreationLead($email);
        //dd($lead);

        $file = "C://xampp8.0/htdocs/pim-gi/public/storage/productthumbs/ptype_1706791744.png";
        $res = $this->testAddFileToSalesQuoteLead($lead, $file);
        //dd($res);

        $lead = new CRMLead();
        //$lead->createFileLeadBody(1,"dsjkfh","/storage/productthumbs/ptype_1706791744.png");
        //$res=$lead->createFileLeadBody($lead->leadid,$lead->leadparentobjectid,$file);
        //dd($res);


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

        return 'OK ->';
    }
}
