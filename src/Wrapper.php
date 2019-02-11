<?php
/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 24/06/2018
 * Time: 21:45
 */

namespace Eduardoavargas\Pipefy;


use function count;
use GuzzleHttp\Psr7\Request;
use function GuzzleHttp\Psr7\str;
use function is_array;
use function json_decode;
use Kreait\Firebase\Util\JSON;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Integer;
use function rand;
use function response;
use function Sodium\library_version_major;

class Wrapper
{

    /*  public $key = '';
      public $myId = null;
      protected $organizationID = null;
      protected $pipeIds = [];
    */

    private $curl;

    public function __construct()
    {
        //$this->config =
        $this->organizationID = '128830';
        $this->key = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJ1c2VyIjp7ImlkIjoyMTgzNTgsImVtYWlsIjoiZWR1YXJkb0BiZXN0c2YuY29tLmJyIiwiYXBwbGljYXRpb24iOjg3Nzh9fQ.2A4dE71r5m3h-hfFnKWr1UwcS0VUb4vJzHQErKEi9v5Vk268roNzJ86zxzssNG_iovx_tNxuUwuN4OxBv1LXPw';//$this->config->get('pipefy.pipefy_org_id');//$this->config->get('pipefy.pipefy_token');
        $this->myId = '';//$this->config->get('pipefy.pipefy_user_id');
        $this->pipeIds = [];
        $this->curl = curl_init();
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }

    public function getUsers()
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, "{
		  \"query\": \"{ organization(id:" . $this->organizationID . "){ id, members { user { id, name, email, username, avatarUrl } } } }\"
		}");
        $responseArray = $this->runCurl();
        return $responseArray->data->organization->members;
    }

    private function runCurl()
    {
        curl_setopt($this->curl, CURLOPT_URL, "https://app.pipefy.com/queries");
//        curl_setopt($this->curl, CURLOPT_URL, "http://private-a5740f-pipefypipe.apiary-mock.com/queries?update_card=update_card");
//        curl_setopt($this->curl, CURLOPT_URL, "https://private-anon-b8c5605554-pipefypipe.apiary-mock.com/queries");
//        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, FALSE);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->curl, CURLOPT_HEADER, FALSE);
        curl_setopt($this->curl, CURLOPT_POST, TRUE);
        curl_setopt($this->curl, CURLOPT_ENCODING, "utf-8");
//        curl_setopt($this->curl, CURLOPT_VERBOSE, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
//            'X-User-Email: eduardo@bestsf.com.br',
//            'X-User-Token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJ1c2VyIjp7ImlkIjoyMTgzNTgsImVtYWlsIjoiZWR1YXJkb0BiZXN0c2YuY29tLmJyIiwiYXBwbGljYXRpb24iOjUxMjJ9fQ.RPS5GfhihY44e9LcMbMV43xTxkKxd0oaKPD4eGK3zNAYHZq_-o12Dd55wClP-SsCw4izClpkwOxAy7Pl9a6Hrg'
            "Authorization: Bearer " . $this->key
        ));
        $result = curl_exec($this->curl);
        return json_decode($result);
    }

    public function mudaPhase($pipe, $de, $para)
    {

        $idcards = "{\"query\": \" { phase(id: " . $de . ") { cards{ edges { node { id } } } } }\"}";

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $idcards);
        $responseArray = $this->runCurl();
        foreach ($responseArray->data->phase->cards->edges as $pipes) {
            for ($i = 0; $i < count($pipes); $i++) {
                $phases[] = $pipes;
            }

        }
        foreach ($phases as $node) {
            foreach ($node as $node1) {
                foreach ($node1 as $ids) {

                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, "{
		  \"query\": \"mutation { moveCardToPhase( input: {  card_id: " . $ids . "  destination_phase_id: " . $para . " } ) { card { id current_phase{ name } } } }\"
		}");
                    $response1 = $this->runCurl();

                }
            }
        }


        $response1 = $this->runCurl();
        if ($response1) {
            return ($response1);
        }
        return false;
    }

    public function createCardCgv($atributos)
    {
        if($atributos->restricao == 'Sim'){
            $label = "{field_id: \\\"label\\\", field_value: [2490543]}";
        }else{
            $label = "";
        }

        if ($atributos->email !== 'teste@bestsf.com.br') {
            $jessika = 234347;
            // $c = rand(true, false);
            // if ($c) {
            $user = $jessika;
            //  } else {
            //    $user = $katieli;
            //  }
        } else {
            $user = 218358;
        }

        $createcard = "{
		  \"query\": \"mutation { createCard(input: {pipe_id: " . $atributos->pipe . " fields_attributes: [{field_id:  \\\"respons_vel\\\", field_value: \\\"" . $user . "\\\" }{field_id:  \\\"nome_1\\\", field_value: \\\"" . $atributos->nome . "\\\" }{field_id: \\\"email\\\", field_value: \\\"" . $atributos->email . "\\\"} {field_id: \\\"telefone_1\\\", field_value: \\\"" . $atributos->telefone . "\\\"} {field_id: \\\"valor_do_cr_dito\\\", field_value: \\\"" . $atributos->valor . "\\\"} {field_id: \\\"opera_o_1\\\", field_value: \\\"" . $atributos->tipo . "\\\"} {field_id: \\\"marca_modelo_1\\\", field_value: \\\"" . $atributos->marca_modelo . "\\\"} {field_id: \\\"ano_do_ve_culo\\\", field_value: \\\"" . $atributos->ano_veiculo . "\\\"} {field_id: \\\"valor_do_veiculo_1\\\", field_value: \\\"" . $atributos->valor_veiculo . "\\\"} {field_id: \\\"valor_da_renda_1\\\", field_value: \\\"" . $atributos->valor_da_renda_1 . "\\\"}  {field_id: \\\"quantidades_de_parcelas\\\", field_value: \\\"" . $atributos->prazo . "\\\"} {field_id: \\\"cpf\\\", field_value: \\\"" . $atributos->cpf . "\\\"} {field_id: \\\"cidade_estado_1\\\", field_value: \\\"" . $atributos->cidade_estado . "\\\"} {field_id: \\\"valor_da_parcela\\\", field_value: \\\"" . $atributos->valor_parcela . "\\\"}{field_id: \\\"ltv_max_ve_culo\\\", field_value: \\\"" . $atributos->ltv . "\\\"}{field_id: \\\"melhor_hor_rio\\\", field_value: \\\"" . $atributos->horario . "\\\"}".$label."{field_id: \\\"origem\\\", field_value: \\\"Robo Best\\\"}]}) { card { id } } }\"
		}";
        /*
         *  assignee_ids: [8159222]
         */
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $createcard);

        $response = $this->runCurl();
        if (!array_key_exists("errors", $response)) {
            return true;
        }
        return false;
    }

    public function createCardCgi($atributos)
    {

        /*
         * Canova 215608
         * Gaspar Filho 218985
         * Naila 218994
         * Jessik 218988
         * Gabi 218984
         *
         * danielin 234347
         * gesiel 300056
         *
         *  30 > 99 jess e gabi
         *  > 100 < 200 naila
         *  > 200 naila canova gaspar
         */



        if ($atributos->email !== 'teste@bestsf.com.br') {

            if ($atributos->valor < 29999 || $atributos->valor_do_imovel < 100000) {
               // $response = ['error' => true, 'message' => 'Valor do crédito muito baixo'];
                return false;
            }
            if ($atributos->valor > 29999 && $atributos->valor < 200000 && $atributos->valor_do_imovel > 99000 ){  //era 100000 Naila de ferias
                $c = rand(true, false);
                if ($c) {
                    $user = 234347;
                } else {
                    $user = 300056;
                }

            }else
           /*if ($atributos->valor > 99999 && $atributos->valor < 200000){
                $user = 218994;
            }else*/
            if ($atributos->valor > 199999  && $atributos->valor < 1000000 && $atributos->valor_do_imovel > 99000){
                $c = rand(1,3);
                switch ($c){
                    case 1:
                        $user = 215608;
                        break;
                    case 2:
                        $user = 218985;
                        break;
                    case 3:
                        $user = 218994;
                        break;
                }
            }else if($atributos->valor > 999999  && $atributos->valor_do_imovel > 99000){
                $user = 218985;
            }

        }else if ($atributos->valor > 999999 && $atributos->valor_do_imovel > 99000){
            $user = 218985;
        } else {
            $user = 218358;
        }

    /*    if($atributos->valor_do_imovel > 1000000){
            $user = 218985;
        }*/
//        $user = 218985;

        $createcard = "{
		  \"query\": \"mutation { createCard(input: {pipe_id: " . $atributos->pipe . " fields_attributes: [{field_id:  \\\"perfil_do_imovel\\\", field_value: \\\"" . $atributos->perfil_do_imovel . "\\\" }{field_id:  \\\"perfil_de_renda\\\", field_value: \\\"" . $atributos->perfil_de_renda . "\\\" }{field_id:  \\\"data_de_nascimento\\\", field_value: \\\"" . $atributos->data_de_nascimento . "\\\" }{field_id:  \\\"respons_vel\\\", field_value: \\\"" . $user . "\\\" }{field_id:  \\\"nome_1\\\", field_value: \\\"" . $atributos->nome . "\\\" }{field_id: \\\"email\\\", field_value: \\\"" . $atributos->email . "\\\"} {field_id: \\\"telefone_1\\\", field_value: \\\"" . $atributos->telefone . "\\\"} {field_id: \\\"valor_do_cr_dito\\\", field_value: \\\"" . $atributos->valor . "\\\"}{field_id: \\\"valor_do_im_vel\\\", field_value: \\\"" . $atributos->valor_do_imovel . "\\\"}{field_id: \\\"renda\\\", field_value: \\\"" . $atributos->valor_da_renda_1 . "\\\"}{field_id: \\\"cpf\\\", field_value: \\\"" . $atributos->cpf . "\\\"}{field_id: \\\"cidade\\\", field_value: \\\"" . $atributos->cidade . "\\\"}{field_id: \\\"origem\\\", field_value: \\\"" . $atributos->origem . "\\\"}]}) { card { id } } }\"
		}";
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $createcard);

        $response = $this->runCurl();
        if (!array_key_exists("errors", $response)) {
            return $response->data->createCard->card;
        }


        return false;
    }

    public function createCardCgvRestricao($atributos)
    {

        if ($atributos->email !== 'teste@bestsf.com.br') {
            $jessika = 218989;
            // $c = rand(true, false);
            // if ($c) {
            $user = $jessika;
            //  } else {
            //    $user = $katieli;
            //  }
        } else {
            $user = 218358;
        }

        $createcard = "{
		  \"query\": \"mutation { createCard(input: {pipe_id: " . $atributos->pipe . " fields_attributes: [{field_id:  \\\"respons_vel\\\", field_value: \\\"" . $user . "\\\" }{field_id:  \\\"nome_1\\\", field_value: \\\"" . $atributos->nome . "\\\" }{field_id: \\\"email\\\", field_value: \\\"" . $atributos->email . "\\\"} {field_id: \\\"telefone_1\\\", field_value: \\\"" . $atributos->telefone . "\\\"} {field_id: \\\"valor_do_cr_dito\\\", field_value: \\\"" . $atributos->valor . "\\\"} {field_id: \\\"opera_o_1\\\", field_value: \\\"" . $atributos->tipo . "\\\"} {field_id: \\\"marca_modelo_1\\\", field_value: \\\"" . $atributos->marca_modelo . "\\\"} {field_id: \\\"ano_do_ve_culo\\\", field_value: \\\"" . $atributos->ano_veiculo . "\\\"} {field_id: \\\"valor_do_veiculo_1\\\", field_value: \\\"" . $atributos->valor_veiculo . "\\\"} {field_id: \\\"valor_da_renda_1\\\", field_value: \\\"" . $atributos->valor_da_renda_1 . "\\\"}  {field_id: \\\"quantidades_de_parcelas\\\", field_value: \\\"" . $atributos->prazo . "\\\"} {field_id: \\\"cpf\\\", field_value: \\\"" . $atributos->cpf . "\\\"} {field_id: \\\"cidade_estado_1\\\", field_value: \\\"" . $atributos->cidade_estado . "\\\"} {field_id: \\\"valor_da_parcela\\\", field_value: \\\"" . $atributos->valor_parcela . "\\\"}{field_id: \\\"ltv_max_ve_culo\\\", field_value: \\\"" . $atributos->ltv . "\\\"}{field_id: \\\"melhor_hor_rio\\\", field_value: \\\"" . $atributos->horario . "\\\"}{field_id: \\\"origem\\\", field_value: \\\"Base Restricao\\\"}]}) { card { id } } }\"
		}";
        /*
         *  assignee_ids: [8159222]
         */
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $createcard);

        $response = $this->runCurl();
        if (!array_key_exists("errors", $response)) {
            return true;
        }
        return false;
    }

    public function createCardConsignado($atributos)
    {
        $createcard = "{
        \"query\": \"mutation { createCard(input: {pipe_id: " . $atributos->pipe . "fields_attributes: [{field_id: \\\"dados_adv\\\",field_value:\\\"" . $atributos->adv . "\\\"}{field_id: \\\"o_qu\\\",field_value:\\\"" . $atributos->nome . "\\\"}{field_id: \\\"telefone\\\",field_value:\\\"" . $atributos->telefone . "\\\"}{field_id: \\\"telefone_2\\\",field_value:\\\"" . $atributos->telefone_2 . "\\\"}{field_id: \\\"e_mail\\\",field_value:\\\"" . $atributos->email . "\\\"}{field_id: \\\"valor\\\",field_value:\\\"" . $atributos->valor . "\\\"}{field_id: \\\"numero_do_beneficio\\\",field_value:\\\"" . $atributos->beneficio . "\\\"}{field_id: \\\"cpf\\\",field_value:\\\"" . $atributos->cpf . "\\\"}{field_id: \\\"cidade_estado\\\",field_value:\\\"" . $atributos->cidade . " / " . $atributos->estado . "\\\"}{field_id: \\\"prazo\\\",field_value:\\\"" . $atributos->prazo . "\\\"}{field_id: \\\"tipo_de_beneficio\\\",field_value:\\\"" . $atributos->tipo_de_beneficio . "\\\"}{field_id: \\\"melhor_hor_rio_atendimento\\\",field_value:\\\"" . $atributos->horario . "\\\"}{field_id: \\\"origem\\\", field_value: \\\"Robo\\\"}{field_id: \\\"respons_vel\\\",field_value:\\\"" . $atributos->responsavel . "\\\"}]}){ card { id } } }\"
        }";

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $createcard);

        $response = $this->runCurl();
        return $response;
        if (!array_key_exists("errors", $response)) {
            return true;
        }
        return false;
    }

    public function createCardSeguro($atributos)
    {
        // Ketiely 233893
        //Glauci 231588
        if ($atributos->email !== 'teste@bestsf.com.br') {

            $c = rand(true, false);
            if ($c) {
                $user = 233893;
            } else {
                $user = 231588;
            }
        } else {
            $user = 218358;
        }

        $createcard = "{
        \"query\": \"mutation { createCard(input: {pipe_id: " . $atributos->pipe . "fields_attributes: [{field_id: \\\"nome\\\",field_value:\\\"" . $atributos->nome . "\\\"}{field_id: \\\"telefone\\\",field_value:\\\"" . $atributos->telefone . "\\\"}{field_id: \\\"e_mail\\\",field_value:\\\"" . $atributos->email . "\\\"}{field_id: \\\"respons_vel\\\",field_value:\\\"" . $user . "\\\"}]}){ card { id } } }\"
        }";

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $createcard);
        $response = $this->runCurl();
        if ($response) {
            return true;
        }
        return false;
    }

    public function getLabel ($card){
            $query = "{
            \"query\": \"query { card(id: ". $card ." ) { labels { id } } }\"
            }";
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $query);

        $response = $this->runCurl();
        $labels = null;
        if (array_key_exists("errors", $response)) {
            return false;
        }
        foreach ($response->data->card->labels as $label) {
            $labels[] = $label->id;
        }
        return $labels;
    }

    public function insertLabel (Int $card, Array $newLabels){
        $query = "{
            \"query\": \"query { card(id: ". $card ." ) { labels { id } } }\"
            }";
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $query);

        $response = $this->runCurl();
        $labels = null;
        if (array_key_exists("errors", $response)) {
            return false;
        }
        if(count($response->data->card->labels)) {
            foreach ($response->data->card->labels as $label) {
                $labels[] = $label->id;
            }
            for ($i=0; $i<count($newLabels);$i++) {
                array_push($labels, $newLabels[$i]);
            }
            $l =implode(",", $labels);
        }else{
           $l =implode(",",$newLabels);
        }

        $query = "{
            \"query\": \"mutation { updateCard(input: { id: ". $card ." label_ids: [".$l."] } ) { clientMutationId } }\"
            }";
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $query);

        $response = $this->runCurl();

        return $response;
    }

    public function mudaFase (Int $card, Int $fase){

        $query = "{
            \"query\": \"mutation { moveCardToPhase(input: { card_id: ". $card ." destination_phase_id: ".$fase." } ){ card { id current_phase{ name } } } }\"
            }";
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $query);

        $response = $this->runCurl();

        return $response;
    }

    public function updateCard($card, array $atributos)
    {
        if (!$card) return response('Informe o card', 401);

        if (!is_array($atributos)) return response('Atributos incorretos', 401);
        foreach ($atributos as $value => $key) {
            $att[] = "field_id: \\\"" . $value . "\\\" new_value: \\\"" . $key . "\\\"";

        }
        for ($i = 0; $i < count($att); $i++) {
            $update = "{
		  \"query\": \"mutation { updateCardField(input: {card_id: " . $card . "  " . $att[$i] . "}) { card { id } } }\"
		}";
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $update);
            $response[] = $this->runCurl();
        }
//        $update = "{
//		  \"query\": \"mutation { updateCard(input: {id: ".$card."  ".$att."}) { card { id } } }\"
//		}";
//
//        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $update);


        if ($response) {
            return $response;
        }
        return false;

    }

    public function deleteCard($id = null, $pipe = null)
    {
        if ($pipe && $id == null) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, "{
		  \"query\": \"{ organization(id: " . $this->organizationID . ") { pipes(ids: [" . $pipe . "]) { phases{ cards { edges { node { id }}}}}}}\"
		}");
            $responseArray = $this->runCurl();
            foreach ($responseArray->data->organization->pipes as $pipes) {
                for ($i = 0; $i < count($pipes->phases); $i++) {
                    $phases[] = $pipes->phases[$i]->cards->edges;
                }

            }
            foreach ($phases as $node) {
                foreach ($node as $node1) {
                    foreach ($node1 as $ids) {
                        if ($ids->id != 8818780) {
                            curl_setopt($this->curl, CURLOPT_POSTFIELDS, "{
		  \"query\": \"mutation {  deleteCard(input: {id: " . $ids->id . "}) { success} }\"
		}");
                            $response = $this->runCurl();

                        }


                    }
                }
            }
        }

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, "{
		  \"query\": \"mutation {  deleteCard(input: {id: " . $id . "}) { success} }\"
		}");
        $response = $this->runCurl();
        if ($response) {
            return $response;
        }
        return false;
    }

    public function getWebhooks()
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, "{
		  \"query\": \"{ organization(id:" . $this->organizationID . "){ pipes{ id name webhooks{ actions email headers id name url } } } }\"}");
        $responseArray = $this->runCurl();
        if (!is_null($responseArray)) {
            foreach ($responseArray->data->organization->pipes as $pipe) {
                $response[] = (Object)$pipe;
            }
            return $response;
        }
        return response()->json('error', 500);//->data->organization->members;
    }

    public function setWebhooks($pipeId, $name, $email, $url, $actions, $headers)
    {
        $actions = addslashes($actions);
        $headers = json_encode($headers);
        $query = (string)"{
            \"query\": \"mutation { createWebhook(input: { pipe_id: ".$pipeId." name: \\\"" . $name . "\\\" email: \\\"" . $email . "\\\" url: \\\"" . $url . "\\\" actions: " . $actions . " headers: " . $headers . " }) { webhook { id name } } }\"
            }";
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $query);
        return $responseArray = $this->runCurl();
        if (!is_null($responseArray)) {
            foreach ($responseArray->data->organization->pipes as $pipe) {
                $response[] = (Object)$pipe;
            }
            return $response;
        }
        return response()->json('error', 500);//->data->organization->members;
    }

    public function myCards()
    {
        return $this->userCards($this->myId);
    }

    public function userCards($userId = null)
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, "{
		  \"query\": \"{ organization(id: " . $this->organizationID . "){ pipes { id, name, phases { id, name, cards( search:{assignee_ids:[" . $userId . "]}) {  edges{ node { url, id, title, due_date, assignees{id, name, username, email }, fields{ name, value, phase_field { id } } } }  } } } } }\"
		}");
        $pipesArray = $this->runCurl();
        $myPipes = [];
        if (!is_null($pipesArray)) {
            foreach ($pipesArray->data->organization->pipes as $pipe) {
                $insert = false;
                $myCards = [];
                if (count($pipe->phases) > 0) {
                    foreach ($pipe->phases as $phase) {
                        $color = PipeConfig::getPhaseColor($phase->id);
                        if ($color !== false) {
                            if (count($phase->cards->edges) > 0) {
                                $insert = true;
                                foreach ($phase->cards as $card) {
                                    foreach ($card as $node) {
                                        $node->node->phaseName = $phase->name;
                                        $node->node->phaseId = $phase->id;
                                        $node->node->color = $color;
                                        $myCards[] = $node->node;
                                    }
                                }
                            }
                        }
                    }
                }
                if ($insert) {
                    $myPipes[] = [
                        'pipeId' => $pipe->id,
                        'pipeName' => $pipe->name,
                        'pipeCards' => $myCards
                    ];
                }
            }
        }
        return $myPipes;
    }

    public function onlyPipes($userId = null)
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, "{
		  \"query\": \"{ organization(id: " . $this->organizationID . "){  pipes { name id phases { id name } } } }\"
		}");
        $pipesArray = $this->runCurl();
        return $pipesArray->data->organization->pipes;
    }

    public function cardDetail($cardId = null)
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, "{
  \"query\": \"{ card(id: " . $cardId . ") { title assignees { id } comments { text author { id } created_at } current_phase { name } done due_date fields { name value } phases_history { phase { name } firstTimeIn } url } }\"
}");
        $card = $this->runCurl();
        return $card->data->card;
    }

    public function comment($cardId, $comment)
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, "{
		  \"query\": \"mutation { createComment(input: {card_id: " . $cardId . " text: \\\"" . $comment . "\\\" }) { comment { text author { id } created_at } } }\"
		}");
        $comment = $this->runCurl();
        if ($comment->data) {
            return $comment->data->createComment->comment;
        }
        return false;
    }

    public function filterCards(Filters $filter)
    {
        $assignees = [];
        foreach ($filter->assignees as $assignee) {
            $assignees[] = $assignee->assignee_id;
        }
        if (!is_null($filter->team)) {
            foreach ($filter->team->members as $member) {
                $assignees[] = $member->member_id;
            }
        }
        $owners = [];
        foreach ($filter->owners as $owner) {
            $owners[] = $owner->owner_id;
        }
        $phases = [];
        foreach ($filter->phases as $phase) {
            $phases[] = $phase->phase_id;
        }
        $assignees = array_unique($assignees);
        $owners = array_unique($owners);
        $phases = array_unique($phases);
        $assignees = !empty($assignees) ? implode(', ', $assignees) : false;
        $search = false;
        if ($assignees) {
            $search = '( search:{assignee_ids:[' . $assignees . ']}) ';
        }
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, "{
		  \"query\": \"{ organization(id: " . $this->organizationID . "){ pipes { id, name, phases { id, name, cards " . $search . "{  edges{ node { createdBy{ id, name }, url, id, title, due_date, assignees{id, name, username, email }, fields{ name, value, phase_field { id } } } }  } } } } }\"
		}");
        $pipesArray = $this->runCurl();
        $myPipes = [];
        if (!is_null($pipesArray)) {
            foreach ($pipesArray->data->organization->pipes as $pipe) {
                $insert = false;
                $myCards = [];
                if (count($pipe->phases) > 0) {
                    foreach ($pipe->phases as $phase) {
                        $color = PipeConfig::getPhaseColor($phase->id);
                        $color = (!$color) ? '#2579a9' : $color;
                        if (!empty($phases) && in_array($phase->id, $phases)) {
                            if (count($phase->cards->edges) > 0) {
                                $insert = true;
                                foreach ($phase->cards as $card) {
                                    foreach ($card as $node) {
                                        if (!empty($owners) && in_array($node->node->createdBy->id, $owners)) {
                                            $node->node->phaseName = $phase->name;
                                            $node->node->phaseId = $phase->id;
                                            $node->node->color = $color;
                                            $myCards[] = $node->node;
                                        } elseif (empty($owners)) {
                                            $node->node->phaseName = $phase->name;
                                            $node->node->phaseId = $phase->id;
                                            $node->node->color = $color;
                                            $myCards[] = $node->node;
                                        }
                                    }
                                }
                            }
                        } elseif (empty($phases)) {
                            if (count($phase->cards->edges) > 0) {
                                $insert = true;
                                foreach ($phase->cards as $card) {
                                    foreach ($card as $node) {
                                        if (!empty($owners) && in_array($node->node->createdBy->id, $owners)) {
                                            $node->node->phaseName = $phase->name;
                                            $node->node->phaseId = $phase->id;
                                            $node->node->color = $color;
                                            $myCards[] = $node->node;
                                        } elseif (empty($owners)) {
                                            $node->node->phaseName = $phase->name;
                                            $node->node->phaseId = $phase->id;
                                            $node->node->color = $color;
                                            $myCards[] = $node->node;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if ($insert) {
                    $myPipes[] = [
                        'pipeId' => $pipe->id,
                        'pipeName' => $pipe->name,
                        'pipeCards' => $myCards
                    ];
                }
            }
        }
        return $myPipes;
    }

    public function getMyId($token)
    {
        $this->key = $token;
        $me = $this->me();
        return $me->id;
    }

    public function me()
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, "{
		  \"query\": \"{ me { id, name, username, avatar_url, email, locale, time_zone } }\"
		}");
        $responseArray = $this->runCurl();
        return $responseArray->data->me;
    }

}
