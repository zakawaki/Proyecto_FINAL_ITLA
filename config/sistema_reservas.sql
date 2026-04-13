DROP DATABASE IF EXISTS sistema_reservas;
CREATE DATABASE sistema_reservas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_reservas;

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO roles (nombre) VALUES
('admin'),
('cliente');

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    password VARCHAR(100) NOT NULL,
    rol_id INT NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

select * from usuarios;

CREATE TABLE tipos_servicio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT
);

INSERT INTO tipos_servicio (nombre, descripcion) VALUES
('hotel', 'Reservas de habitaciones de hotel'),
('medico', 'Reservas de consultorios o citas médicas'),
('cancha', 'Reservas de canchas deportivas');

CREATE TABLE recursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_servicio_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    ubicacion VARCHAR(150),
    capacidad INT DEFAULT 1,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado ENUM('disponible', 'mantenimiento', 'inactivo') DEFAULT 'disponible',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tipo_servicio_id) REFERENCES tipos_servicio(id)
);

CREATE TABLE horarios_recurso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recurso_id INT NOT NULL,
    dia_semana ENUM('lunes','martes','miercoles','jueves','viernes','sabado','domingo') NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    FOREIGN KEY (recurso_id) REFERENCES recursos(id) ON DELETE CASCADE
);

CREATE TABLE estados_reserva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO estados_reserva (nombre) VALUES
('pendiente'),
('confirmada'),
('cancelada'),
('completada');

CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    recurso_id INT NOT NULL,
    estado_reserva_id INT NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    cantidad_personas INT DEFAULT 1,
    monto_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    observaciones TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (recurso_id) REFERENCES recursos(id),
    FOREIGN KEY (estado_reserva_id) REFERENCES estados_reserva(id)
);

CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT NOT NULL,
    metodo_pago ENUM('efectivo', 'tarjeta', 'transferencia') NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado_pago ENUM('pendiente', 'pagado', 'fallido') DEFAULT 'pendiente',
    referencia VARCHAR(100),
    FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE
);

CREATE INDEX idx_reservas_recurso_fecha ON reservas(recurso_id, fecha_inicio, fecha_fin);
CREATE INDEX idx_reservas_usuario ON reservas(usuario_id);
CREATE INDEX idx_recursos_tipo ON recursos(tipo_servicio_id);

INSERT INTO usuarios (
    nombre, apellido, correo, telefono, password, rol_id, estado
) VALUES (
    'Administrador',
    'Principal',
    'admin@sistema.com',
    '8090000000',
    'admin123',
    1,
    'activo'
);

INSERT INTO recursos (tipo_servicio_id, nombre, descripcion, ubicacion, capacidad, precio, estado) VALUES
(1, 'Habitación 101', 'Habitación sencilla con aire acondicionado', 'Hotel Central', 2, 2500.00, 'disponible'),
(1, 'Habitación 202', 'Habitación doble con balcón', 'Hotel Central', 4, 4200.00, 'disponible'),
(2, 'Consultorio A', 'Consultorio general', 'Centro Médico Salud', 1, 1500.00, 'disponible'),
(3, 'Cancha 1', 'Cancha de baloncesto techada', 'Complejo Deportivo Este', 10, 3000.00, 'disponible');

INSERT INTO horarios_recurso (recurso_id, dia_semana, hora_inicio, hora_fin) VALUES
(3, 'lunes', '08:00:00', '20:00:00'),
(3, 'martes', '08:00:00', '20:00:00'),
(3, 'miercoles', '08:00:00', '20:00:00'),
(3, 'jueves', '08:00:00', '20:00:00'),
(3, 'viernes', '08:00:00', '20:00:00');