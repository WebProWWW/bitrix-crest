<?php

class BitrixCRM
{
    public $name = '';
    public $phone = '';
    public $email = '';
    public $comments = '';

    public $utmCampaign = '';
    public $utmContent = '';
    public $utmMedium = '';
    public $utmSource = '';
    public $utmTerm = '';

    private $_fields = [];
    private $_sourceId = '';

    public function __construct($webHookUrl, $sourceId='')
    {
        define('C_REST_LOG_TYPE_DUMP', false);
        define('C_REST_BLOCK_LOG', true);
        define('C_REST_WEB_HOOK_URL', $webHookUrl);
        $this->_sourceId = $sourceId;
    }

    public function setField($name, $val)
    {
        $this->_fields[$name] = $val;
    }

    /**
     * @return bool
     */
    public function send()
    {
        $this->_fields['SOURCE_ID'] = $this->_sourceId;
        $this->_fields['TITLE'] = $this->name;
        $this->_fields['NAME'] = $this->name;
        $this->_fields['COMMENTS'] = $this->comments;
        $this->_fields['UTM_CAMPAIGN'] = $this->utmCampaign;
        $this->_fields['UTM_CONTENT'] = $this->utmContent;
        $this->_fields['UTM_MEDIUM'] = $this->utmMedium;
        $this->_fields['UTM_SOURCE'] = $this->utmSource;
        $this->_fields['UTM_TERM'] = $this->utmTerm;
        if ($this->phone) {
            $this->_fields['PHONE'] = [[
                'VALUE' => $this->phone,
                'VALUE_TYPE' => 'MOBILE'
            ]];
        }
        if ($this->isEmailValid()) {
            $this->_fields['EMAIL'] = [[
                'VALUE' => $this->email,
                'VALUE_TYPE' => 'WORK'
            ]];
        }
        try {
            $result = CRest::call('crm.lead.add', ['fields' => $this->_fields]);
            if (is_array($result) && !array_key_exists('error', $result)) {
                return true;
            }
            return false;
        } catch (Exception $exception) {}
        return false;
    }

    /**
     * @return bool
     */
    private function isEmailValid()
    {
        $regex = "/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/";
        return preg_match($regex, $this->email);
    }
}
