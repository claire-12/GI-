<?php

use GuzzleHttp\Client;
class CRMContact
{
    public string $name = "";
    public string $company = "";
    public string $firstname = "";
    public string $lastname = "";
    public string $phone = "";
    public string $email = "";
    public string $mobile = "";
    public string $contactId = "";
    public string $accountId = "";
    public string $sapAccountId = "";

    public function __construct(string $email = "")
    {
        $this->email = $email;
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
        if ($this->contactId != "") {
            $contact['ContactID'] = $this->contactId;
        }
        if ($this->accountId != null) {
            $contact["AccountPartyID"] = $this->accountId;
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
            $this->contactId = $contact->ContactID;
            $this->accountId = $contact->AccountID;
            $this->sapAccountId = $contact->ExternalID;
        }
    }
}

class CRMLead
{
    /***
     * Create lead Marketing body based on contact details
     */
    public function createMarketingLeadBody($CRMContact, $brand = "")
    {
        $lead = $CRMContact->toArray();
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
     * Create lead Marketing body based on contact details
     */
    public function createKMILeadBody($CRMContact, $listComm, $listOptions)
    {
        //default lead data
        $lead = [
            "Name" => "KMI_" . date('d/m/Y') . "_" . substr($CRMContact->email, strpos($CRMContact->email, '@') + 1),
            "ContactAllowedCode" => "1", // Always send this for KMI Scenario
            "OrganisationAccountContactAllowedCode" => "1", // Always send this for KMI Scenario
            "ContactMobile" => $CRMContact->mobile,
            "Business_KUT" => "141",    // Always send this 141 is GI
            "LeadLifecycle_KUT" => "131",  //  Always send this for KMI Scenario
            "LeadType_KUT" => "105",  //   Always send this 105  is GI
            "Segment" => "GI",  // Always send
            "OriginTypeCode" => "Z38",  // Always send this Z38 is GI Website
            //"ProductofInterest_KUT"=> "321,351,371", we don't have this in our interface
        ];

        if ($CRMContact->contactid != "") { // specific fields for existing contacts
            $lead["ContactID"] = $CRMContact->contactid;
            $lead["AccountPartyID"] = $CRMContact->accountid;
            //if contact has account create it as html data....kinda stupid
            $lead["ContactDataToBeUpdated_KUT"] = $this->getRichTextCommOptions($CRMContact, $listComm, $listOptions);
        } else {
            $lead["Company"] = substr($CRMContact->email, strpos($CRMContact->email, '@') + 1);
            $lead["ContactFirstName"] = substr($CRMContact->email, 0, strpos($CRMContact->email, '@'));
            $lead["ContactLastName"] = "N/A";
            $lead["ContactEMail"] = $CRMContact->email;
            //add kmi comunication items
            $commitems = [];
            foreach ($listComm as $item => $option) {
                array_push($commitems, $this->getKMIComunicationItem($item, $option));
            }
            $lead["LeadMarketingPermissionChannelPermission"] = $commitems;
            // add subscription options
            $options = [];
            foreach ($listOptions as $item => $option) {
                array_push($options, $this->getOptionItem($item, $option));
            }
            $lead["LeadMarketingPermissionCommTypePermission"] = $options;
        }

        $body = json_encode($lead);
        return $body;
    }

    private function getRichTextCommOptions($CRMContact, $listComm, $listOptions)
    {
        $baseStr = "<div>";
        $baseStr .= "Contact Mobile: " . $CRMContact->mobile . "<br>";
        $baseStr .= "Contact Email: " . $CRMContact->email . "<br><br>";
        $baseStr .= "Contact Preference:<br>";

        foreach ($listComm as $item => $option) {
            $baseStr .= $item . " : " . $option ? "Yes" : "No" . "<br>";
        }
        $baseStr .= "<br>";
        foreach ($listOptions as $item => $option) {
            $baseStr .= $item . " : " . $option ? "Yes" : "No" . "<br>";
        }
        $baseStr .= "</div>";
        return $baseStr;
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
    private string $baseURL = "https://my336469.crm.ondemand.com/sap/c4c/odata/v1/c4codataapi/";

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
            $url = $this->baseURL;
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
            $lst[] = $resp;
            $lst[] = $response->getHeaders()['Set-Cookie'][0] . ';' . $response->getHeaders()['Set-Cookie'][1];
        } catch (Exception $e) {
            return null;
        }
        //return $resp;
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
            'content-type' => 'application/json',
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

    private function getSalesQuoteCollectionByBuyerId($buyerId)
    {
        $client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "SalesQuoteCollection";
        $url = $url . '?$filter=BuyerPartyID eq \'' . $buyerId . '\'';
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
    private function getLeadCollection($leadId = null)
    {
        $client = new Client(); //GuzzleHttp\Client
        $url = $this->baseURL . "LeadCollection"; //LeadCollection
        if ($leadId != null) {
            $url = $url . '?$filter=ID eq \'' . $leadId . '\'';
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

    private function createSalesQuoteBody($accountId)
    {
        $body = json_encode([
            "BuyerID" => $accountId,
            "Name" => "Test Sales Quote Creataion JM from code V1",
            "ProcessingTypeCode" => "ZGI",
            "BuyerPartyID" => $accountId,
            "ProductRecipientPartyID" => $accountId,
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

    private function createSalesQuote($token, $accountId)
    {
        $url = $this->baseURL . "SalesQuoteCollection";
        $headers = $this->createPostHeader($token);
        $body = $this->createSalesQuoteBody($accountId);

        return $this->makePostRequest($url, $headers, $body);
    }

    private function createLeadWithoutAccount($email, $token = null)
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


    protected function createLead($CRMContact, $token = null)
    {
        if ($token == null) {
            $token = $this->GetXCSRFToken();
        }
        //get mandatory fields from contact
        $contact = $this->getContactByEmail($CRMContact->email);

        if ($contact == []) { // if contact does not exists, create lead without contact details
            //$account=$this->getAccount($accountid,$token);
            return $this->createLeadWithoutAccount($CRMContact->email);
        } else {
            //fetch contactid
            $contactid = $contact->ContactID;
            //fetch accountid
            $accountid = $contact->AccountID;
            $account = $this->getAccount($accountid, $token);
            $accountpartyid = $account->ExternalID;
            $body = $this->createLeadBody($CRMContact->email, $contactid, $accountpartyid);
            $option = $this->createKMIComunicationItem("tel", true);
        }

        $url = $this->baseURL . "LeadCollection";
        $headers = $this->createPostHeader($token);

        return $this->makePostRequest($url, $headers, $body);
    }

    /***
     * Create KMI Lead. CRMContact must have email defined
     */
    protected function createKMILead($CRMContact, $communicationOptions, $itemOptions, $token = null)
    {
        if ($token == null) {
            $token = $this->GetXCSRFToken();
        }
        //get mandatory fields from contact
        $contact = $this->getContactByEmail($CRMContact->email); // get contact from crm
        if (!empty($CRMContact)) {
            $CRMContact->fillContactFromCRMContactObject($contact); // fill the contact with SAP CRM data
        }

        $lead = new CRMLead();
        $body = $lead->createKMILeadBody($CRMContact, $communicationOptions, $itemOptions);

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

    public function CRMTester()
    {
        $email = 'hliu@summitbiosciences.com';
        //$email='jmartins@infolabix.com';

        //$lead=$this->testKMILeadCreation($email);
        //dd($lead);


        //$lead=$this->createLeadUsingRegisteredAccount('ricardo.castro@infolabix.com','1032212');
        //'hliu@summitbiosciences.com'
        //$lead=$this->createLeadUsingRegisteredAccount('hliu@summitbiosciences.com','1032212');
        $lead = $this->createLead('hliu@summitbiosciences.com', '1032212');
        dd($lead);
        $token = $this->GetXCSRFToken();
        if ($token != null) {
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

            $contact = $this->getContactByEmail($token, "hliu@summitbiosciences.com");
            //dd($contact);
            $contactdetail = $contact->d->results;

            if ($contactdetail == []) {
                dd('N/A');
            } else {
                //fetch contactid
                $contactid = $contactdetail->ContactID;
                //fetch accountid
                $accountid = $contactdetail->AccountID;

            }


            dd($contactdetail);

            //$lead=$this->createLeadWithoutAccount($token,'jose.martins@infolabix.com');
            //dd($lead->d->results);
            //$leadid=$lead->d->results->ID;
            $leadid = 2274;
            //$leadid=null;
            $leadlist = $this->getLeadCollection($leadid);
            dd($leadlist->d->results); // lists array of leads
            return 'OK ->' . $token;
        } else {
            return 'FAIL';
        }
    }
}
