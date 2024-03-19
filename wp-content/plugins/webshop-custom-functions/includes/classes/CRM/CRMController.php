<?php

class CRMController
{
    private string $baseURL;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->baseURL = get_field('crm_base_url', 'option');
        $this->username = get_field('crm_username', 'option');
        $this->password = get_field('crm_password', 'option');
    }

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
            'body' => $body,
            'timeout' => 60
        ));

        if (is_wp_error($response)) {
            debug_log('[CRM makePostRequest ERROR]' . $url, $response->get_error_message() . PHP_EOL . $body);
            return [];
        } else {
            // Get the response body
            $response_body = wp_remote_retrieve_body($response);
            $data = json_decode($response_body);
            debug_log('[CRM makePostRequest SUCCESS]' . $url, $response_body . PHP_EOL . $body);
            if (!empty($data->d->results))
                return $data->d->results;
        }
        return [];
    }

    private function makeGetRequest($url)
    {
        $response = wp_remote_get($url, array(
            'headers' => $this->createGetHeader(),
            'timeout' => 60
        ));

        if (is_wp_error($response)) {
            debug_log('[CRM makeGetRequest ERROR]' . $url, $response->get_error_message());
            return [];
        } else {
            // Get the response body
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body);
            debug_log('[CRM makeGetRequest SUCCESS]' . $url, $body);
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

        return $this->makePostRequest($url, $headers, $body);
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
        if (!empty($crmcontact)) {
            $contact = $this->getContactByEmail($crmcontact->email); // get contact from crm
            $crmcontact->fillContactFromCRMContactObject($contact); // fill the contact with SAP CRM data
        }

        $lead = new CRMLead();
        $body = $lead->createKMILeadBody($crmcontact, $communicationoptions, $itemoptions);
        $url = $this->baseURL . "LeadCollection";
        $headers = $this->createPostHeader($token);

        $request = $this->makePostRequest($url, $headers, $body);

        return $request;
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
        $lead->loadLead($res);
        if ($lead->leadid > 0) {
            if ($crmsalesquote->getFilePath() != null) {
                try {
                    $this->addFileToLead($lead, $crmsalesquote->getFilePath(), $token);
                } catch (Exception $e) {
                    echo 'Caught exception: ', $e->getMessage(), "\n";
                }
            }
        }
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
        return $res;
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
        return $lead ?? null;
    }

    public function processContactUsSubmit($contactForm)
    {
        $crmcontact = new CRMContact($contactForm['your-email']);
        $contact = $this->getContactByEmail($crmcontact->email);
        if (!empty($contact)) {
            $crmcontact->fillContactFromCRMContactObject($contact);
        } else {
            $crmcontact->company = $contactForm['your-company-sector'];
            $crmcontact->lastname = $contactForm['last-name'];
            $crmcontact->firstname = $contactForm['first-name'];
            $crmcontact->mobile = $contactForm['mobile'];
            $crmcontact->jobtitle = $contactForm['job-title'];
            //$crmcontact->jobfunction = $contactForm['function'];
            $crmcontact->jobfunction = $crmcontact->getFunctionCode((string)$contactForm['function']);
        }

        return $this->createContactUsLead($crmcontact, $contactForm['your-message'], $contactForm['product']);
    }

    public function processKMILeadCreation($data)
    {
        $CRMContact = new CRMContact($data['email']);
        if ($data['sms']) {
            $CRMContact->phone = $data['sms'];
        }

        /*$communicationOptions = [
            "tel" => false,
            "whatsapp" => (bool)$data['whatsapp'],
            "sms" => (bool)$data['sms']
        ];*/

        $communicationOptions = [
            "tel" => true,
            "whatsapp" => false,
            "sms" => false
        ];

        $lead = $this->createKMILead($CRMContact, $communicationOptions, $data['options']);
        return $lead;
    }

    public function processAccountCreationLead($data)
    {
        $account = new CRMAccount();
        $crmcontact = new CRMContact($data['user_email']);
        $contact = $this->getContactByEmail($crmcontact->email); // get contact from crm
        if (!empty($contact)) {
            $crmcontact->fillContactFromCRMContactObject($contact); // fill the contact with SAP CRM data
            return;
        } else {
            $account->company = $data['company-name'];
            $account->firstname = $data['first-name'];
            $account->lastname = 'N/A';
            $account->email = $data['user_email'];
            $account->mobile = $data['billing_phone'];
            $account->jobfunction = $crmcontact->getFunctionCode((string)$data['function']);
            $account->department = $data['department'];
            $account->vatnumber = $data['billing_vat'];
            $account->address = $data['billing_address_1'];
            $account->city = 'N/A';
            $account->state = 'N/A';
            $account->country = $data['billing_country'];
            $account->postalcode = 'N/A';
        }
        return $this->createAccountLead($account);
    }

    public function processRequestAQuoteSubmit($data)
    {
        $crmcontact = new CRMContact($data['email']);
        $contact = $this->getContactByEmail($crmcontact->email);
        if (!empty($contact)) {
            $crmcontact->fillContactFromCRMContactObject($contact);
        } else {
            $crmcontact->email = $data['email'];
            $crmcontact->company = $data['company'];
            $crmcontact->firstname = $data['first_name'];
            $crmcontact->lastname = $data['last_name'];
            $crmcontact->mobile = $data['mobile'];
            $crmcontact->jobtitle = $data['jobtitle'];
            $crmcontact->city = $data['billing_city'];
            //$crmcontact->address = $data['billing_address_1'];
            $crmcontact->street = $data['billing_address_1'];
            $crmcontact->housenumber = $data['billing_address_2'] ?? "";
            $crmcontact->state = $data['billing_state'] ?? "";
            $crmcontact->postalcode = $data['billing_postcode'];
            $crmcontact->country = $data['billing_country'];
            $crmcontact->jobfunction = $crmcontact->getFunctionCode((string)$data['function']);
        }

        $crmquoteproduct = new CRMQuoteProduct();

        if ($data['volume'] == "") $data['volume'] = null;
        $crmquoteproduct->quantity = $data['volume'] ?? '0';

        //$crmquoteproduct->quantity = $data['volume'];
        //$crmquoteproduct->quantitycode = "T3";
        $crmquoteproduct->application = $data['application'];
        $crmquoteproduct->requiredby = "next week"; // free text
        $crmquoteproduct->partnumber = $data['part-number'] ?? 'N/A'; // free text
        $crmquoteproduct->comments = $data['additional-information'];  // free text
        $crmquoteproduct->material = $data['material'] ?? '';
        $crmquoteproduct->hardness = $data['o_ring']['hardness'] ?? 'N/A';
        $crmquoteproduct->product = $data['product']; // product of interest
        $crmquoteproduct->dimensions = $data['dimension'] ?? 'N/A';
        $crmquoteproduct->dimensionscode = "T3"; //1000pc
        // end of required in SAP method, but not available in interface
        $crmquoteproduct->compound = $data['o_ring']['compound'] ?? '';
        $crmquoteproduct->temperature = $data['o_ring']['temperature'] ?? '';
        $crmquoteproduct->coating = $data['o_ring']['coating'] ?? '';
        $crmquoteproduct->brand = $data['brand'] ?? 'N/A';

        if ($crmquoteproduct->product === '321') {
            $crmquoteproduct->dimid = $data['dimension_oring']['id'] ?? '0';
            $crmquoteproduct->dimidcode = $data['dimension_oring']['type'] ?? 'INH';
            $crmquoteproduct->dimod = $data['dimension_oring']['od'] ?? '0';
            $crmquoteproduct->dimodcode = $data['dimension_oring']['type'] ?? 'INH';
            $crmquoteproduct->dimwidth = $data['dimension_oring']['width'] ?? '0';
            $crmquoteproduct->dimwidthcode = $data['dimension_oring']['type'] ?? 'INH';
        } else {
            $crmquoteproduct->dimid = '0';
            $crmquoteproduct->dimidcode = "INH";
            $crmquoteproduct->dimod = "0";
            $crmquoteproduct->dimodcode = "INH";
            $crmquoteproduct->dimwidth = "0";
            $crmquoteproduct->dimwidthcode = "INH";
        }


        $crmquote = new CRMSalesQuote($crmcontact, $crmquoteproduct, $data['file'] ?? null);
        /** end of create contact object to use **/
        $lead = $this->createSalesQuoteLead($crmquote);

        return $lead;
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

        $lead = $this->testKMILeadCreation($email);
        //dd($lead);
        $file = "C://xampp8.0/htdocs/pim-gi/public/storage/productthumbs/ptype_1706791744.png";
        $lead = $this->testSalesQuoteLeadCreation($email, $file);
        //dd($lead);

        //$lead=$this->testContactUsLead($email);
        //dd($lead);

        $lead = $this->testAccountCreationLead($email);
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
