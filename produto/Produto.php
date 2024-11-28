<?php
class Produto
{
    private $id;
    private $nome;
    private $descricao;
    private $preco;
    private $quantidade;
    private $categoria;
    private $disponivel;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function setPreco($preco)
    {
        if ($preco > 0) {
            $this->preco = $preco;
        } else {
            throw new Exception("O preço não pode ser negativo ou zero.");
        }
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function setQuantidade($quantidade)
    {
        if ($quantidade >= 0) {
            $this->quantidade = $quantidade;
            $this->atualizarDisponibilidade();
        } else {
            throw new Exception("A quantidade não pode ser negativa.");
        }
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    public function isDisponivel()
    {
        return $this->disponivel;
    }

    private function atualizarDisponibilidade()
    {
        $this->disponivel = $this->quantidade > 0;
    }

    public function diminuirEstoque($quantidade)
    {
        if ($this->quantidade < $quantidade) {
            throw new Exception("Estoque insuficiente para o produto {$this->nome}.");
        }
        $this->quantidade -= $quantidade;
        $this->atualizarDisponibilidade();
    }
  
}

?>