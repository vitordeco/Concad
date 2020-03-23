<?php
namespace Tropaframework\Helper;

/**
 * @author Vitor Deco
 */
class Format
{
	/**
	 * format cnpj or cpf
	 * @return string
	 */
	public static function formatCnpjCpf($value)
	{
        $cnpj_cpf = preg_replace("/\D/", '', $value);

        if (strlen($cnpj_cpf) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        }

        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
	}

    /**
     * format phone
     * @return string
     */
    public static function formatPhone($value)
    {
        $value = preg_replace("/\D/", '', $value); //alias ^0-9

        if( strlen($value) === 8 )
        {
            return preg_replace("/(\d{4})(\d{4})/", "(XX) \$1-\$2", $value);
        }

        if( strlen($value) === 10 )
        {
            return preg_replace("/(\d{2})(\d{4})(\d{4})/", "(\$1) \$2-\$3", $value);
        }

        return preg_replace("/(\d{2})(\d{5})(\d{4})/", "(\$1) \$2-\$3", $value);
    }
}