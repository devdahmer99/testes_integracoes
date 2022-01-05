<?php

namespace Alura\Leilao\Tests\Integration\Dao;


use Alura\Leilao\Infra\ConnectionCreator;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{
    private static $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new \PDO('sqlite: memory');
        self::$pdo->exec('create table leiloes (
                id INTEGER primary key,
                descricao  TEXT,
                finalizado BOOL,
                dataInicio TEXT
            );
');
    }

    public function setUp(): void
    {

        self::$pdo->beginTransaction();
    }
    
    
    /**
     * @throws \Exception
     */
    public function testInserindoEBuscando()
    {
        // Montando o Cenário
      $leilao = new Leilao('Variante 0Km');
      $leilaoDao = new LeilaoDao(self::$pdo);
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
    }

    public function tearDown(): void
    {
        self::$pdo->rollBack();
    }
}