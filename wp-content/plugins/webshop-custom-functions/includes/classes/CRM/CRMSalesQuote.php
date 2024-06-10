<?php
class CRMSalesQuote
{
    public function __construct(CRMContact $crmcontact, CRMQuoteProduct $product, $filepath = null)
    {
        $this->changeContact($crmcontact);
        $this->product = $product;
        $this->filepath = $filepath;
        $this->brand = $product->brand;
    }

    /**
     * Define contact and updates quote lead contact details
     */
    public function changeContact(CRMContact $crmcontact)
    {
        $this->crmcontact = $crmcontact;
        $this->name = "Sales Quote_" . date('d/m/Y') . "_" . substr($crmcontact->email, strpos($crmcontact->email, '@') + 1);
        $this->company = $crmcontact->company ?? substr($crmcontact->email, strpos($crmcontact->email, '@') + 1);
        $this->contactfirstname =$crmcontact->firstname!=""?$crmcontact->firstname: substr($crmcontact->email, 0, strpos($crmcontact->email, '@'));
        $this->contactlastname =$crmcontact->lastname!=""?$crmcontact->lastname: "N/A";
        $this->contactemail = $crmcontact->email;
        $this->contactmobile = $crmcontact->mobile;
        $this->contactfunction = $crmcontact->function;
        $this->contactjobtitle = $crmcontact->jobtitle;
        $this->contactaddress = $crmcontact->address;
        $this->contactcity = $crmcontact->city;
        $this->contactcountry = $crmcontact->country;
        $this->contactpostalcode = $crmcontact->postalcode;
        $this->contactstreet = $crmcontact->street;
        $this->contactstate=$crmcontact->state;
        $this->contacthousenumber=$crmcontact->housenumber??"";
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
    protected string $contactstreet = "";
    protected string $contactcity = "";
    protected string $contactcountry = "";
    protected string $contactpostalcode = "";
    protected string $contactstate="";
    protected string $contacthousenumber="";
	protected $policyAgreed;
	protected $marketingAgreed;
    protected ?CRMContact $crmcontact = null;
    protected ?CRMQuoteProduct $product = null;
    protected ?array $filepath;
    protected $brand;

    public function getName()
    {
        return $this->name;
    }

    public function getStreet()
    {
        return $this->contactstreet;
    }

    public function getState()
    {
        return $this->contactstate;
    }

    public function getHouseNumber()
    {
        return $this->contacthousenumber;
    }

    public function getBrand()
    {
        return $this->brand;
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

    public function getFilePath()
    {
        return $this->filepath;
    }

	public function getPolicyAgreed()
	{
		$this->policyAgreed = $this->getProduct()->policyAgreed;
		return 	$this->policyAgreed;
	}

	public function getMarketingAgreed()
	{
		$this->marketingAgreed = $this->getProduct()->marketingAgreed;
		return $this->marketingAgreed;
	}
}
