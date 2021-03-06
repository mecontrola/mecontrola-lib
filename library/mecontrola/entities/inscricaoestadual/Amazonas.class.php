<?php
namespace mecontrola\entities\inscricaoestadual;

use mecontrola\base\Formatter as Formatter;
use mecontrola\base\Validator as Validator;

/**
 * Classe utilizada para fazer a formatação e validação dos dados de inscrição estadual do estado do Amazonas.
 * 
 * @author Rafael Almeida de Oliveira <mecontrola@gmail.com>
 * @namespace mecontrola\entities\inscricaoestadual
 * @since 1.0.20150518
 * @link http://www.sintegra.gov.br/Cad_Estados/cad_AM.html
 * @link http://online.sefaz.am.gov.br/sintegra/index.asp
 */
class Amazonas extends Formatter implements Validator
{
	protected $pattern = '/[0-9]{2}(\.[0-9]{3}){2}-[0-9]/';
	
	/**
	 * Faz a validação da inscrição estadual informada utilizando a rotina de validação do estado do Amazonas.
	 * 
	 * @access public
	 * @param String $value O valor que deve ser verificado.
	 * @return Boolean Retorna <code>TRUE</code> caso sejá um valor válido ou <code>FALSE</code> caso não seja.
	 * @see \mecontrola\base\Validator::isValid()
	 */
	public function isValid($value)
	{
		$value = preg_replace('/[^0-9]/', '', $value);
		
		if(!$this->canBeFormatted($value))
			return FALSE;

		$n = [];
		for($i = 0, $l = strlen($value); $i < $l; $i++)
			$n[] = intval($value[$i]);
		
		$d = self::numberCheck($n);
		
		return $d === intval(substr($value, 8, 1));
	}
	
	/**
	 * Retorna a máscara de formatação da inscrição estadual do estado do Amazonas.
	 * 
	 * @access public
	 * @return String Retorna a máscara.
	 * @see \mecontrola\base\Formatter::getMask()
	 */
	public function getMask()
	{
		return '99.999.999-9';
	}
	
	/**
	 * Gera um número de inscrição estadual do Amazonas formatado ou não. Por padrão <code>$formatar</code> está definido como <code>TRUE</code>.
	 * 
	 * @access public
	 * @param Boolean $formatar Define se o valor retornado será ou não formatado.
	 * @return String A inscrição estadual gerada.
	 */
	public function generate($formatar = TRUE)
	{
		$n = [
				rand(0, 9), rand(0, 9), rand(0, 9),
				rand(0, 9), rand(0, 9), rand(0, 9),
				rand(0, 9), rand(0, 9)
		];
		
		$n[] = self::numberCheck($n);
		
		return ($formatar ? $this->format(implode('', $n)) : implode('', $n));
	}
	
	/**
	 * Realiza o cálculo do digito verificador. 
	 * 
	 * @access private
	 * @param String $value Valor que será usado para o cálculo.
	 * @return Integer Retorna o dígito verificador.
	 */
	private function numberCheck($value)
	{
		$weight = [9, 8, 7, 6, 5, 4, 3, 2];
		
		$total = 0;
		foreach($weight as $i => $v)
			$total += ($value[$i] * $v);
		
		if($total < 11)
		{
			return (11 - $total);
		} else
		{
			$aux = $total % 11;
			if($aux <= 1)
				return 0;
			else 
				return 11 - $aux;
		} 
	}
}