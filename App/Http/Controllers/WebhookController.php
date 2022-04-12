<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\webhook;
use Illuminate\Support\Facades\DB;
use App\Mail\EnviarEmail;

class WebhookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        

      if($this->autentication($request)){

          $webhook = new webhook;
          $vendedor = $this->selecionaVendedor();
          $this->salvaWebhook($webhook, $request, $vendedor);

          $webd = [
            'dados' => json_encode($request->all()),
          ];

          $this->enviaEmail($webd, $vendedor);
      }
      
      else{
            $webd = [
                'dados' => json_encode($request->all())
            ];
            $email = 'suporteti@corretoraportal.com';
            $nome = 'suporteti';

            $this->enviaEmailTest($webd, $email, $nome);
      }
  }
  protected function enviaEmailTest($webd, $email, $nome){

    \App\jobs\EnviaEmail::dispatch($webd, $email, $nome, $leadLow=1); //job
  }

  protected function enviaEmail($webd, $vendedor){

      $email = $vendedor->email; //email do vendedor 
      $nome = $vendedor->nome; // nome do vendedor

      // envia um email para o três endereços, um dele é o endereço do vendedor
      \App\Jobs\EnviaEmail::dispatch($webd, $email, $nome,$leadLow=0); //job
      
  } // end enviaEmail()

  protected function zeraRecebimento(){
      DB::table('vendedores')->update(['recebeu' => 0]);
  } // end zeraRecebimento

  protected function verificaVendedores(){
      $vendedores = DB::table('vendedores')
      ->selectRaw('count(id) as existe')
      ->whereRaw('(peso - recebeu) > 0')
      ->first();

      if($vendedores->existe == 0){
          $this->zeraRecebimento();
      }
  } //end verificaVendedores

  protected function selecionaVendedor(){
      
      $this->verificaVendedores();

      /* Seleciona o vendedor que irá receber a proxima lead os filtros são: (peso - recebeu) caso seja maior que zero
          ordena os vendedores do select por descendencia, logo após, ordena por descendencia a coluna peso
          depois ordena ascendentemente por ids e entrega o primeiro da lista.
      */
      $vendedor = DB::table('vendedores')
      ->selectRaw('*, (peso - recebeu) as nota')
      ->orderByRaw('(peso - recebeu) DESC')
      ->orderByRaw('peso DESC')
      ->orderByRaw('id ASC')
      ->first();

      DB::table('vendedores')
      ->where('id',$vendedor->id)
      ->update(['recebeu' => $vendedor->recebeu+1]);

      return $vendedor;
  } // selecionaVendedor

  protected function salvaWebhook(webhook $data, Request $request, $vendedor){
      $data->dados = json_encode($request->all());
      $data->id_vendedor = $vendedor->id;
      $data->save();
      return $data;
  } // salvaWebhook

  protected function autentication(Request $request){

      if($this->testLeadInconpleta($request)){

          if( $this->autenticarHook($request) ){
              return 1;
          }
          
      }

      return 0;
  }

  protected function autenticarHook(Request $request){

      $listaTestNome = array("testTi","test","teste","testes","suporteTI","suporteTi","suporteti");
      $nome = $request->input("name");

      if(in_array($nome,$listaTestNome)){
          return 0;
      }

      $listaTestEmail = array("teste.com","test.com","suporte.com","corretoraportal.com","corretoraportal.com.br");
      $email = $request->input("email");

      $splitEmail = explode("@",$email); //array

      if(in_array($splitEmail[0],$listaTestNome) || in_array($splitEmail[1],$listaTestEmail)){
          return 0;
      }
      return 1;
  }

  protected function testLeadInconpleta(Request $request){
      if ($request->input("phone") == "" and $request->input("email") == ""){
          return 0;
      }
      return 1;
  }
}
