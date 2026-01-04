USE `if0_40669997_sistema_estoque`;

CREATE TABLE `autenticacao` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(255) NOT NULL,
    `user` VARCHAR(255) NOT NULL UNIQUE,
    `senha` VARCHAR(255) NOT NULL,
    `nivel` ENUM('admin', 'operador', 'analista') NOT NULL,
    `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `produtos` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `usuario_id` INT UNSIGNED NOT NULL,

    `nome` VARCHAR(255) NOT NULL,
    `codigo` VARCHAR(50) NOT NULL UNIQUE,
    `categoria` VARCHAR(255) NOT NULL,

    `qtd_por_lote` INT NOT NULL,
    `qtd_de_lote` INT NOT NULL,

    `preco_pago_lote` DECIMAL(10,2) NOT NULL,
    `valor_revenda_unidade` DECIMAL(10,2) NOT NULL,

    `descricao` TEXT,

    `qtd_total_unidades` INT NOT NULL,

    `lucro_por_lote` DECIMAL(10,2) NOT NULL,
    `lucro_por_unidade` DECIMAL(10,2) NOT NULL,

    PRIMARY KEY (`id`),

    CONSTRAINT `fk_produtos_usuario`
        FOREIGN KEY (`usuario_id`)
        REFERENCES `autenticacao`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;
