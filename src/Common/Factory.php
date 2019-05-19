<?php

namespace NFePHP\NFSeTrans\Common;

/**
 * Class for RPS XML convertion
 *
 * @category  NFePHP
 * @package   NFePHP\NFSeTrans
 * @copyright NFePHP Copyright (c) 2008-2018
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse-etransparencia for the canonical source repository
 */

use stdClass;
use NFePHP\Common\DOMImproved as Dom;
use DOMNode;
use DOMElement;

class Factory
{
    /**
     * @var stdClass
     */
    protected $std;
    /**
     * @var Dom
     */
    protected $dom;
    /**
     * @var DOMElement
     */
    protected $rps;

    /**
     * Constructor
     * @param stdClass $std
     */
    public function __construct(stdClass $std)
    {
        $this->std = $std;

        $this->dom = new Dom('1.0', 'UTF-8');
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = false;
        $this->rps = $this->dom->createElement('nfe:Reg20Item');
        //$att = $this->dom->createAttribute('xmls:nfe');
        //$att->value = 'NFe';
        //$this->rps->appendChild($att);
    }

    /**
     * Builder, converts sdtClass Rps in XML Rps
     * NOTE: without Prestador Tag
     * @return string RPS in XML string format
     */
    public function render()
    {
        $dt = new \DateTime($this->std->dtemi);

        $this->dom->addChild(
            $this->rps,
            "nfe:TipoNFS",
            $this->std->tipo,
            true
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:NumRps",
            $this->std->numero,
            true
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:SerRps",
            $this->std->serie,
            true
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:DtEmi",
            $dt->format('d/m/Y'),
            true
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:RetFonte",
            $this->std->retfonte,
            true
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:CodSrv",
            $this->std->codsrv,
            true
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:DiscrSrv",
            $this->std->discrsrv,
            true
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:VlNFS",
            number_format($this->std->vlnfs, 2, ',', ''),
            true
        );

        if(!empty($this->std->vlded)) {
            $this->dom->addChild(
                $this->rps,
                "nfe:VlDed",
                !empty($this->std->vlded) ? number_format($this->std->vlded, 2, ',', '') : 0,
                true
            );
        }
        $this->dom->addChild(
            $this->rps,
            "nfe:DiscrDed",
            !empty($this->std->discrded) ? $this->std->discrded : '',
            true
        );

        $vlbascalc = $this->std->vlnfs - (!empty($this->std->vlded) ? $this->std->vlded : 0);

        $this->dom->addChild(
            $this->rps,
            "nfe:VlBasCalc",
            number_format($vlbascalc, 2, ',', ''),
            true
        );

        $this->dom->addChild(
            $this->rps,
            "nfe:AlqIss",
            number_format($this->std->alqiss, 2, ',', ''),
            true
        );


        if ($this->std->retfonte == 'SIM') {
            $this->dom->addChild(
                $this->rps,
                "nfe:VlIss",
                '0',
                true
            );
            $this->dom->addChild(
                $this->rps,
                "nfe:VlIssRet",
                number_format($this->std->vlissret, 2, ',', ''),
                true
            );
        } else {
            if($this->std->alqiss > 0) {
                $vliss = round(($this->std->alqiss/100)*$vlbascalc, 2);
                $this->dom->addChild(
                    $this->rps,
                    "nfe:VlIss",
                    number_format($vliss, 2, ',', ''),
                    true
                );
                $this->dom->addChild(
                    $this->rps,
                    "nfe:VlIssRet",
                    '0',
                    true
                );
            }
        }

        //tomador
        $tom = $this->std->tomador;
        $this->dom->addChild(
            $this->rps,
            "nfe:CpfCnpTom",
            $tom->cpfcnpj,
            true
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:RazSocTom",
            !empty($tom->razsoc) ? $tom->razsoc : null,
            false
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:TipoLogtom",
            !empty($tom->tipolog) ? $tom->tipolog : null,
            false
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:LogTom",
            !empty($tom->log) ? $tom->log : null,
            false
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:NumEndTom",
            !empty($tom->numend) ? $tom->numend : null,
            false
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:ComplEndTom",
            !empty($tom->complend) ? $tom->complend : null,
            false
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:BairroTom",
            !empty($tom->bairro) ? $tom->bairro : null,
            false
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:MunTom",
            !empty($tom->mun) ? $tom->mun : null,
            false
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:SiglaUFTom",
            !empty($tom->siglauf) ? $tom->siglauf : null,
            false
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:CepTom",
            !empty($tom->cep) ? $tom->cep : null,
            false
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:Telefone",
            !empty($tom->telefone) ? $tom->telefone : null,
            false
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:InscricaoMunicipal",
            !empty($tom->inscricaomunicipal) ? $tom->inscricaomunicipal : null,
            false
        );
        if (!empty($tom->localprestacao)) {
            $loc = $tom->localprestacao;
            $this->dom->addChild(
                $this->rps,
                "nfe:TipoLogLocPre",
                !empty($loc->tipolog) ? $loc->tipolog : null,
                false
            );
            $this->dom->addChild(
                $this->rps,
                "nfe:LogLocPre",
                !empty($loc->log) ? $loc->log : null,
                false
            );
            $this->dom->addChild(
                $this->rps,
                "nfe:NumEndLocPre",
                !empty($loc->numend) ? $loc->numend : null,
                false
            );
            $this->dom->addChild(
                $this->rps,
                "nfe:ComplEndLocPre",
                !empty($loc->complend) ? $loc->complend : null,
                false
            );
            $this->dom->addChild(
                $this->rps,
                "nfe:BairroLocPre",
                !empty($loc->bairro) ? $loc->bairro : null,
                false
            );
            $this->dom->addChild(
                $this->rps,
                "nfe:MunLocPre",
                !empty($loc->mun) ? $loc->mun : null,
                false
            );
            $this->dom->addChild(
                $this->rps,
                "nfe:SiglaUFLocpre",
                !empty($loc->siglauf) ? $loc->siglauf : null,
                false
            );
            $this->dom->addChild(
                $this->rps,
                "nfe:CepLocPre",
                !empty($loc->cep) ? $loc->cep : null,
                false
            );
        }
        $this->dom->addChild(
            $this->rps,
            "nfe:Email1",
            !empty($tom->email1) ? $tom->email1 : null,
            false
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:Email2",
            !empty($tom->email2) ? $tom->email2 : null,
            false
        );
        $this->dom->addChild(
            $this->rps,
            "nfe:Email3",
            !empty($tom->email3) ? $tom->email3 : null,
            false
        );

        if (!empty($this->std->tributos)) {
            $trib = $this->dom->createElement('nfe:Reg30');
            foreach ($this->std->tributos as $t) {
                $node = $this->dom->createElement('nfe:Reg30Item');
                $this->dom->addChild(
                    $node,
                    "nfe:TributoSigla",
                    $t->sigla,
                    true
                );
                $this->dom->addChild(
                    $node,
                    "nfe:TributoAliquota",
                    number_format($t->aliquota, 2, ',', ''),
                    true
                );
                $this->dom->addChild(
                    $node,
                    "nfe:TributoValor",
                    number_format($t->valor, 2, ',', ''),
                    true
                );
                $trib->appendChild($node);
            }
            $this->rps->appendChild($trib);
        }
        $this->dom->appendChild($this->rps);
        return str_replace(['<?xml version="1.0" encoding="UTF-8"?>', "\n"], ['',''], $this->dom->saveXML());
    }
}
