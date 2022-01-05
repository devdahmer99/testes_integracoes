<?php

namespace Alura\Leilao\Tests\Integration\Dao;


use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Exception;
use PDO as PDOAlias;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{
    private static $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new PDOAlias('sqlite: memory');
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
     * @throws Exception
     * @dataProvider leiloes
     */
    public function testBuscaLeiloesNaoFinalizados(array $leiloes)
    {
        $leilaoDao = new LeilaoDao(self::$pdo);
        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }
      $leiloes = $leilaoDao->recuperarNaoFinalizados();

      self::assertCount(1, $leiloes);
      self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
      self::assertSame(
          'Variante 0Km',
          $leiloes[0]->recuperarDescricao()
      );
    }

    /**
     * @param array $leiloes
     * @dataProvider leiloes
     */
    public function testBuscaLeiloesFinalizados(array $leiloes)
    {
        $leilaoDao = new LeilaoDao(self::$pdo);
        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }
        $leiloes = $leilaoDao->recuperarFinalizados();

        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame(
            'Fia 147 0Km',
            $leiloes[0]->recuperarDescricao()
        );
        self::assertTrue($leiloes[0]->estaFinalizado());
    }


    public function tearDown(): void
    {
        self::$pdo->rollBack();
    }

    public function leiloes(): array
    {
        $naoFinalizado = new Leilao('Variante 0Km');
        $finalizado = new Leilao('Fia 147 0Km');
        $finalizado->finaliza();

        return [
            [
                [
                    $naoFinalizado, $finalizado
                ]
            ]
        ];
    }
}