SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE roles;
DROP TABLE proyecto_blog_usuarios;
DROP TABLE proyecto_blog_categorias;
DROP TABLE proyecto_blog_entradas;
DROP TABLE proyecto_blog_entradas_categorias;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS proyecto_blog_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro DATE NOT NULL,
    rol_id INT NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS proyecto_blog_categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS proyecto_blog_entradas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_creacion DATE NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES proyecto_blog_usuarios(id)
);

CREATE TABLE IF NOT EXISTS proyecto_blog_entradas_categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    entrada_id INT NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES proyecto_blog_categorias(id),
    FOREIGN KEY (entrada_id) REFERENCES proyecto_blog_entradas(id)
);

INSERT INTO roles VALUES(1, "user");
INSERT INTO roles VALUES(2, "admin");
INSERT INTO roles VALUES(3, "owner");

INSERT INTO proyecto_blog_usuarios VALUES(NULL, "Fredy", "Palomino Huamani", "fredy12cbpu@gmail.com", "$2y$04$q8o5gMAnpCsmPN/qwAqNOum2yD3Q8wS/gFepwrNx/yOPr4szMhT9i", CURDATE(), 3);