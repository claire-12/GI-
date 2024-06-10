<?php
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
    public $jobfunction = "";
    public $jobtitle = "";
    public $address = "";
    public $city = "";
    public $country = "";
    public $postalcode = "";
    public $function = "";
    public $street="";
    public $housenumber="";
    public $state="";
	public $agreeTerm = true;
	public $policyAgreed;
	public $marketingAgreed;


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

    public function getFunctionCode(string $department): string
    {
        $departments = array(
            "Z41" => "Accounting",
            "Z42" => "Administrative",
            "Z43" => "Business Development",
            "Z44" => "Consulting",
            "Z45" => "Engineering",
            "Z46"=> "Finance",
            "Z47"=>"Human Resources",
            "Z48"=>"Information Technology",
            "Z49"=>"Marketing",
            "Z50"=>"Operations",
            "Z58"=>"Legal",
            "Z52"=>"Military and Protective Services",
            "Z51"=>"Product Management",
            "Z52"=>"Purchasing",
            "Z53"=>"Quality Assurance",
            "Z54"=>"Research",
            "Z55"=>"Sales",
            "Z56"=>"Support",
        );

        $key = array_search($department, $departments);

        return $key ?? '0001';
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
			$this->agreeTerm = $contact->MarketingConsent_KUT ?? false;
        }
    }
}
