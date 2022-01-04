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
        // Montando o Cenário
      $leilao = new Leilao('Variante 0Km');
      $pdo = ConnectionCreator::getConnection();
      $leilaoDao = new LeilaoDao($pdo);
      $leilaoDao->salva($leilao);

        // Agindo e garantindo que o Cenário esta ok
      $leiloes = $leilaoDao->recuperarNaoFinalizados();

      // Testando se realmente esta batendo com o desenvolvido
      self::assertCount(1, $leiloes);
      self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
      self::assertSame(
          'Variante 0Km',
          $leiloes[0]->recuperarDescricao()
      );
        // Removendo o Cenário
      $pdo->exec('DELETE FROM main.leiloes');
    }
}