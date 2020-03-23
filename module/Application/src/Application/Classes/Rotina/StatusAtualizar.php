<?php
namespace Application\Classes\Rotina;

use Model\ModelInscricao;
use Application\Classes\MailMessage;

/**
 * @author Vitor Deco
 * 
 * /rotina/status-atualizar?token=TR*P@
 */
class StatusAtualizar extends RotinaAbstract
{
    public function render()
    {
        try 
        {
            $inscricoes = $this->selecionarInscricoesParaAtualizar();
//            echo'<pre>'; print_r($inscricoes); exit;
            foreach( $inscricoes as $inscricao )
            {
                $check = $this->verificarStatus($inscricao);
                if( $check )
                {
                    $response = $this->enviarEmail($inscricao);
                    if( !$response ) continue;

                    $this->atualizarStatus($inscricao);
                }
            }
            
            return true;
            
        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }
    }
    
    private function selecionarInscricoesParaAtualizar()
    {
        $where = "(inscricao.status = '" . ModelInscricao::STATUS_PENDENTE . "')";
        $model = new ModelInscricao($this->tb, $this->adapter);
        $result = $model->where($where)->limit(10)->get();
        return $result;
    }

    private function verificarStatus($inscricao)
    {
        $payment = new \Tropaframework\Buy\Payment\PaymentPagseguro($this->layout()->config_pagseguro);
        $bool = $payment->getTransaction($inscricao['id_inscricao']);
        return $bool;
    }

    private function enviarEmail($inscricao)
    {
        $link = 'http://' . $_SERVER['HTTP_HOST'] . '/inscricao/sucesso/' . $inscricao['id_inscricao'];
        $replace = array(
            'nome' => $inscricao['nome'],
            'link' => $link,
        );
        $to = $inscricao['email'];
        $mail = new MailMessage($this->layout()->config_smtp);
        $response = $mail->inscricaoComprovante($to, $replace);
        return $response ? true : false;
    }
    
    private function atualizarStatus($inscricao)
    {
        $set = array();
        $set['status'] = ModelInscricao::STATUS_PAGO;
        $model = new ModelInscricao($this->tb, $this->adapter);
        $model->save($set, $inscricao['id_inscricao']);
    }
}