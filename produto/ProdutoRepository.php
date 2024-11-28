<?php
class ProdutoRepository
{
    //conexao com banco de dados   
    private $conn;

    /**
     * Construtor da classe ProdutoDAO
     * Recebe uma conexão PDO como parâmetro
     * 
     * @param PDO $connection Conexão com o banco de dados
     */
    public function __construct(PDO $connection)
    {
        // Armazena a conexão passada como parâmetro
        $this->conn = $connection;
    }

    public array $listaProdutos = [];

    /**
     * Método para inserir um novo produto no banco de dados
     * 
     * @param Produto $produto Objeto Produto a ser inserido
     * @return int ID do produto inserido
     * @throws Exception Em caso de erro na inserção
     */

    public function criar(Produto $produto)
    {
        try {
            //prepara a query de inserção
            $sql = "INSERT INTO produtos (
 nome, descricao, preco, quantidade, categoria, disponivel) VALUES (?, ?, ?, ?, ?, ?)";
            //prepara o statement
            $stmt = $this->conn->prepare($sql);

            // Bind dos valores com tipos específicos
            // bindValue permite especificar o tipo de dado para cada parâmetro
            $stmt->bindValue(1, $produto->getNome());
            $stmt->bindValue(2, $produto->getDescricao());
            $stmt->bindValue(3, $produto->getPreco(), PDO::PARAM_STR);
            $stmt->bindValue(4, $produto->getQuantidade(), PDO::PARAM_INT);
            $stmt->bindValue(5, $produto->getCategoria());
            $stmt->bindValue(6, $produto->isDisponivel(), PDO::PARAM_BOOL);
            //executa a inserção
            $stmt->execute();
            //retorna o id do ultimo registro inserido
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            //lança uma excessao personalizada
            error_log("Erro ao criar produto: " . $e->getMessage());
            throw new Exception("Não foi possivel adicionar o Produto.");
        }
    }




    public function deletar(Produto $deletar_produto) {}
}
