<?php
namespace Model\Result\PessoaJuridica;
use Model\Result\ResultAbstract;

class ResultPessoaJuridica extends ResultAbstract
{
    //==================
    //RECEITA FEDERAL
    //==================
    protected $nome;
    protected $atividade_principal = array();
    protected $data_situacao;
    protected $telefone;
    protected $email;
    protected $atividades_secundarias = array();
    protected $qsa = array();
    protected $situacao;
    protected $abertura;
    protected $sigla_natureza_juridica;
    protected $natureza_juridica;
    protected $ultima_atualizacao;
    protected $tipo; //Não exibe
    protected $fantasia; //Não exibe
    protected $efr; //Não exibe
    protected $motivo_situacao;
    protected $situacao_especial;
    protected $data_situacao_especial;
    protected $capital_social;
    protected $extra = array(); //Não exibe
    protected $porte;
    protected $ibge = array(); //Não exibe
    protected $uf;
    protected $municipio;
    protected $logradouro;
    protected $complemento;
    protected $cep;
    protected $numero;
    protected $bairro;

    //==================
    //SIMPLES NACIONAL
    //==================
    protected $cnpj_matriz;
    protected $situacao_simples_nacional;
    protected $situacao_simei;
    protected $situacao_simples_nacional_anterior;
    protected $situacao_simei_anterior;
    protected $agendamentos;
    protected $eventos_futuros_simples_nacional;
    protected $eventos_futuros_simei;

    //==================
    //SINTEGRA
    //==================
    protected $nome_empresarial;
    protected $cnpj;
    protected $inscricao_estadual;
    protected $tipo_inscricao;
    protected $data_situacao_cadastral;
    protected $situacao_cnpj;
    protected $situacao_ie;
    protected $nome_fantasia;
    protected $data_inicio_atividade;
    protected $regime_tributacao;
    protected $informacao_ie_como_destinatario;
    protected $porte_empresa;
    protected $cnae_principal = array();
    protected $data_fim_atividade;

    //==================
    //SUFRAMA
    //==================

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return array
     */
    public function getAtividadePrincipal()
    {
        return $this->atividade_principal;
    }

    /**
     * @param array $atividade_principal
     */
    public function setAtividadePrincipal($atividade_principal)
    {
        $this->atividade_principal = $atividade_principal;
    }

    /**
     * @return mixed
     */
    public function getDataSituacao()
    {
        return $this->data_situacao;
    }

    /**
     * @param mixed $data_situacao
     */
    public function setDataSituacao($data_situacao)
    {
        $this->data_situacao = $data_situacao;
    }

    /**
     * @return array
     */
    public function getAtividadesSecundarias()
    {
        return $this->atividades_secundarias;
    }

    /**
     * @param array $atividades_secundarias
     */
    public function setAtividadesSecundarias($atividades_secundarias)
    {
        $this->atividades_secundarias = $atividades_secundarias;
    }

    /**
     * @return array
     */
    public function getQsa()
    {
        return $this->qsa;
    }

    /**
     * @param array $qsa
     */
    public function setQsa($qsa)
    {
        $this->qsa = $qsa;
    }

    /**
     * @return mixed
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * @param mixed $situacao
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
    }

    /**
     * @return mixed
     */
    public function getAbertura()
    {
        return $this->abertura;
    }

    /**
     * @param mixed $abertura
     */
    public function setAbertura($abertura)
    {
        $this->abertura = $abertura;
    }

    /**
     * @return mixed
     */
    public function getSiglaNaturezaJuridica()
    {
        return $this->sigla_natureza_juridica;
    }

    /**
     * @param mixed $sigla_natureza_juridica
     */
    public function setSiglaNaturezaJuridica($sigla_natureza_juridica)
    {
        $this->sigla_natureza_juridica = $sigla_natureza_juridica;
    }

    /**
     * @return mixed
     */
    public function getNaturezaJuridica()
    {
        return $this->natureza_juridica;
    }

    /**
     * @param mixed $natureza_juridica
     */
    public function setNaturezaJuridica($natureza_juridica)
    {
        $this->natureza_juridica = $natureza_juridica;
    }

    /**
     * @return mixed
     */
    public function getUltimaAtualizacao()
    {
        return $this->ultima_atualizacao;
    }

    /**
     * @param mixed $ultima_atualizacao
     */
    public function setUltimaAtualizacao($ultima_atualizacao)
    {
        $this->ultima_atualizacao = $ultima_atualizacao;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getFantasia()
    {
        return $this->fantasia;
    }

    /**
     * @param mixed $fantasia
     */
    public function setFantasia($fantasia)
    {
        $this->fantasia = $fantasia;
    }

    /**
     * @return mixed
     */
    public function getEfr()
    {
        return $this->efr;
    }

    /**
     * @param mixed $efr
     */
    public function setEfr($efr)
    {
        $this->efr = $efr;
    }

    /**
     * @return mixed
     */
    public function getMotivoSituacao()
    {
        return $this->motivo_situacao;
    }

    /**
     * @param mixed $motivo_situacao
     */
    public function setMotivoSituacao($motivo_situacao)
    {
        $this->motivo_situacao = $motivo_situacao;
    }

    /**
     * @return mixed
     */
    public function getSituacaoEspecial()
    {
        return $this->situacao_especial;
    }

    /**
     * @param mixed $situacao_especial
     */
    public function setSituacaoEspecial($situacao_especial)
    {
        $this->situacao_especial = $situacao_especial;
    }

    /**
     * @return mixed
     */
    public function getDataSituacaoEspecial()
    {
        return $this->data_situacao_especial;
    }

    /**
     * @param mixed $data_situacao_especial
     */
    public function setDataSituacaoEspecial($data_situacao_especial)
    {
        $this->data_situacao_especial = $data_situacao_especial;
    }

    /**
     * @return mixed
     */
    public function getCapitalSocial()
    {
        return \Tropaframework\Helper\Convert::toReal($this->capital_social);
    }

    /**
     * @param mixed $capital_social
     */
    public function setCapitalSocial($capital_social)
    {
        $this->capital_social = $capital_social;
    }

    /**
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param array $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

    /**
     * @return mixed
     */
    public function getPorte()
    {
        return $this->porte;
    }

    /**
     * @param mixed $porte
     */
    public function setPorte($porte)
    {
        $this->porte = $porte;
    }

    /**
     * @return mixed
     */
    public function getCnpjMatriz()
    {
        return $this->cnpj_matriz;
    }

    /**
     * @param mixed $cnpj_matriz
     */
    public function setCnpjMatriz($cnpj_matriz)
    {
        $this->cnpj_matriz = $cnpj_matriz;
    }

    /**
     * @return mixed
     */
    public function getSituacaoSimplesNacional()
    {
        return $this->situacao_simples_nacional;
    }

    /**
     * @param mixed $situacao_simples_nacional
     */
    public function setSituacaoSimplesNacional($situacao_simples_nacional)
    {
        $this->situacao_simples_nacional = $situacao_simples_nacional;
    }

    /**
     * @return mixed
     */
    public function getSituacaoSimei()
    {
        return $this->situacao_simei;
    }

    /**
     * @param mixed $situacao_simei
     */
    public function setSituacaoSimei($situacao_simei)
    {
        $this->situacao_simei = $situacao_simei;
    }

    /**
     * @return mixed
     */
    public function getSituacaoSimplesNacionalAnterior()
    {
        return $this->situacao_simples_nacional_anterior;
    }

    /**
     * @param mixed $situacao_simples_nacional_anterior
     */
    public function setSituacaoSimplesNacionalAnterior($situacao_simples_nacional_anterior)
    {
        $this->situacao_simples_nacional_anterior = $situacao_simples_nacional_anterior;
    }

    /**
     * @return mixed
     */
    public function getSituacaoSimeiAnterior()
    {
        return $this->situacao_simei_anterior;
    }

    /**
     * @param mixed $situacao_simei_anterior
     */
    public function setSituacaoSimeiAnterior($situacao_simei_anterior)
    {
        $this->situacao_simei_anterior = $situacao_simei_anterior;
    }

    /**
     * @return mixed
     */
    public function getAgendamentos()
    {
        return $this->agendamentos;
    }

    /**
     * @param mixed $agendamentos
     */
    public function setAgendamentos($agendamentos)
    {
        $this->agendamentos = $agendamentos;
    }

    /**
     * @return mixed
     */
    public function getEventosFuturosSimplesNacional()
    {
        return $this->eventos_futuros_simples_nacional;
    }

    /**
     * @param mixed $eventos_futuros_simples_nacional
     */
    public function setEventosFuturosSimplesNacional($eventos_futuros_simples_nacional)
    {
        $this->eventos_futuros_simples_nacional = $eventos_futuros_simples_nacional;
    }

    /**
     * @return mixed
     */
    public function getEventosFuturosSimei()
    {
        return $this->eventos_futuros_simei;
    }

    /**
     * @param mixed $eventos_futuros_simei
     */
    public function setEventosFuturosSimei($eventos_futuros_simei)
    {
        $this->eventos_futuros_simei = $eventos_futuros_simei;
    }

    /**
     * @return mixed
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @param mixed $telefone
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getNomeEmpresarial()
    {
        return $this->nome_empresarial;
    }

    /**
     * @param mixed $nome_empresarial
     */
    public function setNomeEmpresarial($nome_empresarial)
    {
        $this->nome_empresarial = $nome_empresarial;
    }

    /**
     * @return mixed
     */
    public function getCnpj()
    {
        return \Tropaframework\Helper\Format::formatCnpjCpf($this->cnpj);
    }

    /**
     * @param mixed $cnpj
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
    }

    /**
     * @return mixed
     */
    public function getInscricaoEstadual()
    {
        return $this->inscricao_estadual;
    }

    /**
     * @param mixed $inscricao_estadual
     */
    public function setInscricaoEstadual($inscricao_estadual)
    {
        $this->inscricao_estadual = $inscricao_estadual;
    }

    /**
     * @return mixed
     */
    public function getTipoInscricao()
    {
        return $this->tipo_inscricao;
    }

    /**
     * @param mixed $tipo_inscricao
     */
    public function setTipoInscricao($tipo_inscricao)
    {
        $this->tipo_inscricao = $tipo_inscricao;
    }

    /**
     * @return mixed
     */
    public function getDataSituacaoCadastral()
    {
        return $this->data_situacao_cadastral;
    }

    /**
     * @param mixed $data_situacao_cadastral
     */
    public function setDataSituacaoCadastral($data_situacao_cadastral)
    {
        $this->data_situacao_cadastral = $data_situacao_cadastral;
    }

    /**
     * @return mixed
     */
    public function getSituacaoCnpj()
    {
        return $this->situacao_cnpj;
    }

    /**
     * @param mixed $situacao_cnpj
     */
    public function setSituacaoCnpj($situacao_cnpj)
    {
        $this->situacao_cnpj = $situacao_cnpj;
    }

    /**
     * @return mixed
     */
    public function getSituacaoIe()
    {
        return $this->situacao_ie;
    }

    /**
     * @param mixed $situacao_ie
     */
    public function setSituacaoIe($situacao_ie)
    {
        $this->situacao_ie = $situacao_ie;
    }

    /**
     * @return mixed
     */
    public function getNomeFantasia()
    {
        return $this->nome_fantasia;
    }

    /**
     * @param mixed $nome_fantasia
     */
    public function setNomeFantasia($nome_fantasia)
    {
        $this->nome_fantasia = $nome_fantasia;
    }

    /**
     * @return mixed
     */
    public function getDataInicioAtividade()
    {
        return $this->data_inicio_atividade;
    }

    /**
     * @param mixed $data_inicio_atividade
     */
    public function setDataInicioAtividade($data_inicio_atividade)
    {
        $this->data_inicio_atividade = $data_inicio_atividade;
    }

    /**
     * @return mixed
     */
    public function getRegimeTributacao()
    {
        return $this->regime_tributacao;
    }

    /**
     * @param mixed $regime_tributacao
     */
    public function setRegimeTributacao($regime_tributacao)
    {
        $this->regime_tributacao = $regime_tributacao;
    }

    /**
     * @return mixed
     */
    public function getInformacaoIeComoDestinatario()
    {
        return $this->informacao_ie_como_destinatario;
    }

    /**
     * @param mixed $informacao_ie_como_destinatario
     */
    public function setInformacaoIeComoDestinatario($informacao_ie_como_destinatario)
    {
        $this->informacao_ie_como_destinatario = $informacao_ie_como_destinatario;
    }

    /**
     * @return mixed
     */
    public function getPorteEmpresa()
    {
        return $this->porte_empresa;
    }

    /**
     * @param mixed $porte_empresa
     */
    public function setPorteEmpresa($porte_empresa)
    {
        $this->porte_empresa = $porte_empresa;
    }

    /**
     * @return array
     */
    public function getCnaePrincipal()
    {
        return $this->cnae_principal;
    }
    public function getCnaePrincipalCode()
    {
        return $this->cnae_principal->code;
    }
    public function getCnaePrincipalText()
    {
        return $this->cnae_principal->text;
    }

    /**
     * @param array $cnae_principal
     */
    public function setCnaePrincipal($cnae_principal)
    {
        $this->cnae_principal = $cnae_principal;
    }

    /**
     * @return mixed
     */
    public function getDataFimAtividade()
    {
        return $this->data_fim_atividade;
    }

    /**
     * @param mixed $data_fim_atividade
     */
    public function setDataFimAtividade($data_fim_atividade)
    {
        $this->data_fim_atividade = $data_fim_atividade;
    }

    /**
     * @return mixed
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param mixed $uf
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
    }

    /**
     * @return mixed
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * @param mixed $municipio
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
    }

    /**
     * @return mixed
     */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * @param mixed $logradouro
     */
    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;
    }

    /**
     * @return mixed
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * @param mixed $complemento
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
    }

    /**
     * @return mixed
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * @param mixed $cep
     */
    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @param mixed $bairro
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    /**
     * @return array
     */
    public function getIbge()
    {
        return $this->ibge;
    }

    /**
     * @param array $ibge
     */
    public function setIbge($ibge)
    {
        $this->ibge = $ibge;
    }


}