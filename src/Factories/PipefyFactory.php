<?php namespace PipefyWrapper\Factories;


class PipefyFactory
{
    private $curl;

    public function __construct()
    {
        $this->key = config('pipefy.token');
        $this->url = config('pipefy.baseUrl');
        $this->organization = config('pipefy.org_id');
        $this->curl = curl_init();
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }

    public function runCurl($param)
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->curl, CURLOPT_HEADER, FALSE);
        curl_setopt($this->curl, CURLOPT_POST, TRUE);
        curl_setopt($this->curl, CURLOPT_ENCODING, "utf-8");
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->key
        ));
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $param);
        try {
            $result = curl_exec($this->curl);
            return json_decode($result);
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function arrayToGraphql($data){
        $log_a = "";
        $c =0;
        foreach ($data as $key => $value) {
            if(is_array($value)) {
                $log_a .= $key . "{" . $this->arrayToGraphql($value) . " ";
                $c++;
            }
            else                    $log_a .=  $value." ";
        }
        for($i=0;$i<$c;$i++){
            $log_a .= "}";
        }
        return $log_a;
    }
}