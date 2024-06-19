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
            if (isset($data->d->results) && count($data->d->results) > 0)
                return $data->d->results[0];
        }
        return [];
    }

    public function getAccount($accountid)
    {
        // $client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "CorporateAccountCollection";
        $url = $url . '?$filter=AccountID eq \'' . $accountid . '\'';
        $url = $url . '&$format=json';

        $response = $this->makeGetRequest($url);

        return $response;
    }

    private function getSalesOrg($objectid)
    {
        //$client = new Client(); //GuzzleHttp\Client
        $url=$this->baseURL."CorporateAccountCollection('".$objectid."')/CorporateAccountSalesData";
        $url=$url.'?$format=json';
        $response = $this->makeGetRequest($url);
        return $response;
    }    

    public function getSalesOrganization($accountid)
    {
        $res=$this->getAccount($accountid);
        if (isset($res->ObjectID)){
            $objectid=$res->ObjectID;
            $salesres=$this->getSalesOrg($objectid);
            if(isset($salesres->SalesOrganisationID))
            {
                if(isset(CRMConstant::SALESORGS[$salesres->SalesOrganisationID]))
                    return CRMConstant::SALESORGS[$salesres->SalesOrganisationID];
            }
        }
        //return "2141";
        return "";
    } 



    private function getContactByEmail($email)
    {
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
        //print_r($body);
        //die();
        $url = $this->baseURL . "SalesQuoteCollection";
        $url = $this->baseURL . "LeadCollection";
        $headers = $this->createPostHeader($token);

        $res = $this->makePostRequest($url, $headers, $body);
        $lead->loadLead($res);
        if ($lead->leadid > 0) {
            $files = $crmsalesquote->getFilePath();
            if (is_array($files)) {
                try {
                    foreach ($files as $file) {
                        $this->addFileToLead($lead, $file, $token);
                    }
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
		$contact_marketing_agreed = $contactForm['contact_marketing_agreed'][0];
		$contact_marketing_agreed = $contact_marketing_agreed ? true : false;
        if (!empty($contact)) {
            $crmcontact->fillContactFromCRMContactObject($contact);
			$crmcontact->policyAgreed = $contact_marketing_agreed;
			$crmcontact->agreeTerm = $contact_marketing_agreed;
        } else {
            $crmcontact->company = $contactForm['your-company-sector'];
            $crmcontact->lastname = $contactForm['last-name'];
            $crmcontact->firstname = $contactForm['first-name'];
            $crmcontact->mobile = $contactForm['mobile'];
            $crmcontact->jobtitle = $contactForm['job-title'];
            //$crmcontact->jobfunction = $contactForm['function'];
            $crmcontact->jobfunction = $crmcontact->getFunctionCode((string)$contactForm['function'][0]);
			$crmcontact->policyAgreed = $contact_marketing_agreed;
			$crmcontact->agreeTerm = $contact_marketing_agreed;
        }

        return $this->createContactUsLead($crmcontact, $contactForm['your-message'], $contactForm['product']);
    }

    public function processKMILeadCreation($data)
    {
        $CRMContact = new CRMContact($data['email']);
        if ($data['sms']) {
            $CRMContact->phone = $data['sms'];
        }

		$CRMContact->policyAgreed = $data["marketing_agreed"] == 'yes' ? true : false;
        // KMI POST
        $CRMContact->agreeTerm = $data['marketing_agreed'] == 'yes' ? true : false;
        $CRMContact->marketingAgreed = $data['marketing_agreed'] == 'yes' ? true : false;
        /*$communicationOptions = [
            "tel" => false,
            "whatsapp" => (bool)$data['whatsapp'],
            "sms" => (bool)$data['sms']
        ];*/

        $communicationOptions = [
            "e-mail" => true,
            "tel" => false,
            "whatsapp" => false,
            "sms" => false,
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
            $account->lastname = $data['last-name'];
            $account->email = $data['user_email'];
            $account->mobile = $data['billing_phone'];
            $account->jobfunction = $crmcontact->getFunctionCode((string)$data['function']);
            $account->jobtitle = $data['job-title'];
            $account->department = $data['department'];
            $account->vatnumber = $data['billing_vat'];
            $account->address = $data['billing_address_1'];
            $account->city = $data['billing_city'];
            $account->state = $data['billing_state'];
            $account->country = $data['billing_country'];
            $account->postalcode = $data['billing_postcode'];

			$account->agreeTerm = $data["agree-term-condition"] == "on" ? true : false;
        }

        return $this->createAccountLead($account);
    }

    public function processRequestAQuoteSubmit($data)
    {
		$contact_marketing_agreed = ( $data['rfq_marketing_agreed'] && ($data['rfq_marketing_agreed'] == 1 || $data['rfq_marketing_agreed'] == 'yes') ) ? true : false;
        $crmcontact = new CRMContact($data['email']);
        $contact = $this->getContactByEmail($crmcontact->email);
        if (!empty($contact)) {
            $crmcontact->fillContactFromCRMContactObject($contact);
			$crmcontact->policyAgreed = $contact_marketing_agreed;
			$crmcontact->agreeTerm = $contact_marketing_agreed;
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
			$crmcontact->policyAgreed = $contact_marketing_agreed;
			$crmcontact->agreeTerm = $contact_marketing_agreed;
        }

        $crmquoteproduct = new CRMQuoteProduct();

        if ($data['volume'] == "") $data['volume'] = null;
        $crmquoteproduct->quantity = $data['volume'] ?? '0';

        //$crmquoteproduct->quantity = $data['volume'];
        //$crmquoteproduct->quantitycode = "T3";
        $crmquoteproduct->application = $data['application'];
        $crmquoteproduct->requiredby = $data['when-needed']; // "next week"; // free text
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
		$crmquoteproduct->policyAgreed = $data['rfq_policy_agreed'];
		$crmquoteproduct->marketingAgreed = $contact_marketing_agreed;

        if ($crmquoteproduct->product === '005') {
            $crmquoteproduct->dimid = $data['dimension_oring']['id']!=""?$data['dimension_oring']['id']:"0";
            $crmquoteproduct->dimidcode = $data['dimension_oring']['type']!=""?$data['dimension_oring']['type']: 'INH';
            $crmquoteproduct->dimod = $data['dimension_oring']['od']!=""?$data['dimension_oring']['od']:"0";
            $crmquoteproduct->dimodcode = $data['dimension_oring']['type']!=""?$data['dimension_oring']['type']: 'INH';
            $crmquoteproduct->dimwidth = $data['dimension_oring']['width']!=""?$data['dimension_oring']['width']:"0";
            $crmquoteproduct->dimwidthcode = $data['dimension_oring']['type']!=""?$data['dimension_oring']['type']: 'INH';
        } else {
            $crmquoteproduct->dimid = '0';
            $crmquoteproduct->dimidcode = "INH";
            $crmquoteproduct->dimod = "0";
            $crmquoteproduct->dimodcode = "INH";
            $crmquoteproduct->dimwidth = "0";
            $crmquoteproduct->dimwidthcode = "INH";
        }


        $crmquote = new CRMSalesQuote($crmcontact, $crmquoteproduct, $data['file_path'] ?? null);
        /** end of create contact object to use **/
        $lead = $this->createSalesQuoteLead($crmquote);

        return $lead;
    }
    public function getContactByUserEmail($email)
    {
        return $this->getContactByEmail($email);
    }
}
