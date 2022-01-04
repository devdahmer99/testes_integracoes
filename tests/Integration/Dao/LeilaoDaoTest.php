<?php

namespace Alura\Leilao\Tests\Integration\Dao;


use Alura\Leilao\Infra\ConnectionCreator;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testInserindoEBuscando()
    {
      $leilao = new Leilao('Variante 0Km');
      $leilaoDao = new LeilaoDao(ConnectionCreator::getConnection());

      $leilaoDao->salva($leilao);
      $leiloes = $leilaoDao->recuperarNaoFinalizados();

      self::assertCount(1, $leiloes);
      self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
      self::assertSame(
          'Variante 0Km',
          $leiloes[0]->recuperarDescricao()
      );
    }
}