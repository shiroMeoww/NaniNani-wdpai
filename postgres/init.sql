create table if not exists "user" (
    "uid" uuid primary key default gen_random_uuid(),
    "name" varchar(64) not null,
    "surname" varchar(64) not null,
    "email" varchar(256) not null unique,
    "password" varchar(256) not null,
    "description" varchar(2048) default null,
    "photo" varchar(256) default null
);

create table if not exists "student" (
    "id" serial8 primary key,
    "userUid" uuid not null unique references "user"("uid") on delete cascade,
    "birthdate" timestamp without time zone
);

create table if not exists "teacher" (
    "id" serial8 primary key,
    "userUid" uuid not null unique references "user"("uid") on delete cascade
);

create table if not exists "hobby" (
    "id" serial8 primary key,
    "name" varchar(128) not null
);

create table if not exists "lessonType" (
    "id" serial8 primary key,
    "name" varchar(128) not null
);

create table if not exists "teacherLesson" (
    "teacherId" int8 references "teacher"("id") on delete cascade,
    "lessonTypeId" int8 references "lessonType"("id") on delete cascade,

    constraint "pk_teacherLesson" primary key ("teacherId", "lessonTypeId")
);

create table if not exists "studentHobby" (
    "studentId" int8 references "student"("id") on delete cascade,
    "hobbyId" int8 references "hobby"("id") on delete cascade,

    constraint "pk_studentHobby" primary key ("studentId", "hobbyId")
);

create table if not exists "lesson" (
    "uid" uuid primary key default gen_random_uuid(),
    "datetime" timestamp without time zone not null,
    "duration" int4 not null,
    "type" int8 not null references "lessonType"("id") on delete cascade,
    "studentId" int8 not null references "student"("id") on delete cascade,
    "teacherId" int8 not null references "teacher"("id") on delete cascade
);

create table if not exists "note" (
    "id" serial8 primary key,
    "studentId" int8 not null references "student"("id") on delete cascade, 
    "content" varchar(4096)
);

create table if not exists "group" (
    "uid" uuid primary key default gen_random_uuid(),
    "name" varchar(256) not null,
    "description" varchar(2048) default null,
    "level" int2 not null check ("level" between 1 and 5)
);

create table if not exists "studentGroup" (
    "studentId" int8 references "student"("id") on delete cascade,
    "groupUid" uuid references "group"("uid") on delete cascade,

    constraint "pk_studentGroup" primary key ("studentId", "groupUid")
);
insert into "group" (name, description, level) values
  ('Początkujący N5', 'Grupa dla zupełnych początkujących', 1),
  ('Podstawowy N4', 'Podstawowa gramatyka i słownictwo', 2),
  ('Średni N3', 'Średniozaawansowany japoński', 3),
  ('Zaawansowany N2', 'Zaawansowana gramatyka i kanji', 4),
  ('Ekspercki N1', 'Najwyższy poziom biegłości', 5);
