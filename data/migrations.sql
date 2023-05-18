drop database if exists org_db;
create database if not exists org_db;

use org_db;

create table users (
    id int auto_increment primary key,
    login varchar(100) not null,
    password varchar(255) not null
);

create table departments (
    id int(11) unsigned auto_increment primary key,
    name varchar(100) not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp
);

create table employees (
    id int(11) unsigned auto_increment primary key,
    last_name varchar(100) not null,
    first_name varchar(100) not null,
    middle_name varchar(100) default '',
    birth_date date not null,
    birth_place varchar(255) not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp on update current_timestamp
);

create table department_employees (
    department_id int(11) unsigned not null,
    employee_id int(11) unsigned not null,
    foreign key (department_id) references departments (id) on delete no action,
    foreign key (employee_id) references employees (id) on delete no action,
    primary key (department_id, employee_id)
);

-- Seeding departments table --

insert into departments (name) values ('Stuff department');
insert into departments (name) values ('Strategy planning department');
insert into departments (name) values ('Human resource department');
insert into departments (name) values ('Administration');
insert into departments (name) values ('Logistic');

-- Seeding employees table --

insert into employees (last_name, first_name, middle_name, birth_date, birth_place) values ('Иванов', 'Иван', 'Иванович', '1992-03-15', 'Москва');
insert into employees (last_name, first_name, middle_name, birth_date, birth_place) values ('Артемьев', 'Андрей', 'Лукович', '1984-02-01', 'Республика Татарстан город С длинным названием');
insert into employees (last_name, first_name, middle_name, birth_date, birth_place) values ('John', 'Snow', '', '2003-01-01', 'Winter Fell (arrived to the Night Watch)');