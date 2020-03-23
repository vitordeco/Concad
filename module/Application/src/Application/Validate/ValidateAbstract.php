<?php
namespace Application\Validate;

/**
 * @author: Vitor Deco
 */
abstract class ValidateAbstract
{
    //lista de erros
    const ERROR_SENDMAIL = "Houve um problema ao enviar o e-mail, tente novamente!";
    const ERROR_DATABASE = "Houve um problema ao salvar, tente novamente!";
    const ERROR_REQUIRED = "Preencha todos os campos obrigatórios!";
    const ERROR_SENHA_CONFIRMAR = "A confirmação da senha está incorreta!";
    const ERROR_EMAIL_CONFIRMAR = "A confirmação do e-mail está incorreta!";
    const ERROR_EMAIL_DUPLICIDADE = "O e-mail informado já se encontra cadastrado em nossos registros!";
    const ERROR_EMAIL = "O e-mail informado não é válido!";
    const ERROR_CPF = "O CPF informado não é válido!";
    const ERROR_CNPJ = "O CNPJ informado não é válido!";
    const ERROR_CPF_CNPJ = "O CPF/CNPJ informado não é válido!";
    const ERROR_DATE = "A data informada é inválida!";
    const ERROR_TERMOS = "É necessário ler e aceitar os termos de uso para continuar!";
    const ERROR_CAPTCHA = "É necessário selecionar a caixa informando que você não é um robô!";
    const ERROR_CHECKITEM = "Selecione pelo menos um item para executar essa ação!";
    const ERROR_LOGIN = "Usuário ou senha inválido!";
    const ERROR_PERMISSAO_ACESSO = "Você não tem permissão para acessar essa página!";
    const ERROR_PASSWORD_FORCE = 'Crie uma senha com pelo menos 6 caracteres, contendo no mínimo 6 caracteres, com letras maiúsculas, minúsculas, números e caracteres especiais como @ ou #.';

    //lista de sucessos
    const SUCCESS_DEFAULT = "Salvo com sucesso!";
    const SUCCESS_SENDMAIL = "Envio realizado com sucesso!";
    const SUCCESS_ACTION = "Ação executada com sucesso!";
}