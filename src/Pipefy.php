<?php namespace EduardoAVargas\Pipefy;

use EduardoAVargas\Pipefy\Factories\PipefyFactory;
use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;

class Pipefy extends AbstractManager
{

    /**
     * The factory instance.
     *
     * @var \Mautic\Factory
     */
    protected $factory;

    /**
     * Create a new Mautic manager instance.
     *
     * @param $config
     * @param $factory
     *
     * @return void
     */
    public function __construct(Repository $config, PipefyFactory $factory)
    {
        parent::__construct($config);

        $this->factory = $factory;
    }

    /**
     * Create the connection instance.
     *
     * @param array $config
     *
     * @return mixed
     */
    protected function createConnection(array $config)
    {
        return $this->factory->make($config);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName()
    {
        return 'pipefy';
    }

    /**
     * Get the factory instance.
     *
     * @return \Mautic\MauticFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }


    /**
     * @param null $param
     * @return bool|false|string
     */
    public function get($parameters)
    {
        try {
            $param = "{
		  \"query\": \"{ organization(id: ". $this->factory->organization ."){".$this->factory->arrayToGraphql($parameters)."} }\"
		}";
          return $response = $this->factory->runCurl($param);
        }catch (\Exception $e){
            report($e);
            return $e;
        }
    }

    /**
     * @param array $parameters
     *
     * @param string $oq
     *
     * @param  array $callback
     * @return
     */
    public function set(array $parameters, string $oq, array $callback)
    {
        $v = '';
        foreach ($parameters as $key => $value) {
            $v .= $key ." : " .$value ."";
        }
        $param = "{
		  \"query\": \" mutation { ".$oq."(input: { ". $v ." } ) { ". $this->factory->arrayToGraphql($callback) ." } }\"
		}";

       // dd($param);
        try {
            $param = "{
		  \"query\": \" mutation { ".$oq."(input: { ". $v ." } ) { ". $this->factory->arrayToGraphql($callback) ." } }\"
		}";
            return $response = $this->factory->runCurl($param);
        }catch (\Exception $e){
            report($e);
            return $e;
        }
    }

    public function createCard(array $parameters, string $oq, array $callback)
    {
        $v = '';
        foreach ($parameters as $key => $value) {

            if(is_array($value)){
                $i = 0;
                $len = count($value);
                $v .= $key." [ ";
                foreach ($value as $keys => $values){
                    $i = 0;
                    $len = count($value);
                    foreach ($values as $keys => $values){
                        if ($i == $len - 1){
                            $v .= $keys .": " .$values .   " }";
                        }else {
                            $v .= "{ " .$keys . ": " . $values . ",";
                        }
                        $i++;
                    }}
                $v .= " ]";
            }else

                $v .= $key ." : " .$value ." ";
        }
        dump($v);
        try {
            $param = "{
		  \"query\": \" mutation { ".$oq."(input: { ". $v ." } ) { ". $this->factory->arrayToGraphql($callback) ." } }\"
		}";
            return $response = $this->factory->runCurl($param);
        }catch (\Exception $e){
            report($e);
            return $e;
        }
    }


}
