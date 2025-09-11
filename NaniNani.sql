--
-- PostgreSQL database dump
--

-- Dumped from database version 17.6
-- Dumped by pg_dump version 17.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: group; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."group" (
    uid uuid DEFAULT gen_random_uuid() NOT NULL,
    name character varying(256) NOT NULL,
    description character varying(2048) DEFAULT NULL::character varying,
    level smallint NOT NULL,
    CONSTRAINT group_level_check CHECK (((level >= 1) AND (level <= 5)))
);


ALTER TABLE public."group" OWNER TO postgres;

--
-- Name: hobby; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hobby (
    id bigint NOT NULL,
    name character varying(128) NOT NULL
);


ALTER TABLE public.hobby OWNER TO postgres;

--
-- Name: hobby_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.hobby_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.hobby_id_seq OWNER TO postgres;

--
-- Name: hobby_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.hobby_id_seq OWNED BY public.hobby.id;


--
-- Name: lesson; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lesson (
    uid uuid DEFAULT gen_random_uuid() NOT NULL,
    datetime timestamp without time zone NOT NULL,
    duration integer NOT NULL,
    type bigint NOT NULL,
    "studentId" bigint NOT NULL,
    "teacherId" bigint NOT NULL
);


ALTER TABLE public.lesson OWNER TO postgres;

--
-- Name: lessonType; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."lessonType" (
    id bigint NOT NULL,
    name character varying(128) NOT NULL
);


ALTER TABLE public."lessonType" OWNER TO postgres;

--
-- Name: lessonType_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."lessonType_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."lessonType_id_seq" OWNER TO postgres;

--
-- Name: lessonType_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."lessonType_id_seq" OWNED BY public."lessonType".id;


--
-- Name: note; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.note (
    id bigint NOT NULL,
    "studentId" bigint NOT NULL,
    content character varying(4096)
);


ALTER TABLE public.note OWNER TO postgres;

--
-- Name: note_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.note_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.note_id_seq OWNER TO postgres;

--
-- Name: note_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.note_id_seq OWNED BY public.note.id;


--
-- Name: student; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.student (
    id bigint NOT NULL,
    "userUid" uuid NOT NULL,
    birthdate timestamp without time zone
);


ALTER TABLE public.student OWNER TO postgres;

--
-- Name: studentGroup; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."studentGroup" (
    "studentId" bigint NOT NULL,
    "groupUid" uuid NOT NULL
);


ALTER TABLE public."studentGroup" OWNER TO postgres;

--
-- Name: studentHobby; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."studentHobby" (
    "studentId" bigint NOT NULL,
    "hobbyId" bigint NOT NULL
);


ALTER TABLE public."studentHobby" OWNER TO postgres;

--
-- Name: student_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.student_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.student_id_seq OWNER TO postgres;

--
-- Name: student_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.student_id_seq OWNED BY public.student.id;


--
-- Name: teacher; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.teacher (
    id bigint NOT NULL,
    "userUid" uuid NOT NULL
);


ALTER TABLE public.teacher OWNER TO postgres;

--
-- Name: teacherLesson; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."teacherLesson" (
    "teacherId" bigint NOT NULL,
    "lessonTypeId" bigint NOT NULL
);


ALTER TABLE public."teacherLesson" OWNER TO postgres;

--
-- Name: teacher_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.teacher_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.teacher_id_seq OWNER TO postgres;

--
-- Name: teacher_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.teacher_id_seq OWNED BY public.teacher.id;


--
-- Name: user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."user" (
    uid uuid DEFAULT gen_random_uuid() NOT NULL,
    email character varying(256) NOT NULL,
    password character varying(256) NOT NULL,
    description character varying(2048) DEFAULT NULL::character varying,
    photo character varying(256) DEFAULT NULL::character varying,
    name character varying(64) NOT NULL,
    surname character varying(64) NOT NULL,
    level character(2)
);


ALTER TABLE public."user" OWNER TO postgres;

--
-- Name: hobby id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hobby ALTER COLUMN id SET DEFAULT nextval('public.hobby_id_seq'::regclass);


--
-- Name: lessonType id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."lessonType" ALTER COLUMN id SET DEFAULT nextval('public."lessonType_id_seq"'::regclass);


--
-- Name: note id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.note ALTER COLUMN id SET DEFAULT nextval('public.note_id_seq'::regclass);


--
-- Name: student id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.student ALTER COLUMN id SET DEFAULT nextval('public.student_id_seq'::regclass);


--
-- Name: teacher id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teacher ALTER COLUMN id SET DEFAULT nextval('public.teacher_id_seq'::regclass);


--
-- Data for Name: group; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."group" (uid, name, description, level) FROM stdin;
67d9e108-03b0-400a-87e7-649bd04d648a	grupka	uczymy sie i jest fajnie	3
636b2a92-610e-4c30-a9f1-1d17d2e4ddcc	grupka2	uczymy sie ale jest niefajnie	2
b8dda51a-62bd-45fd-9abd-5e627e443835	grupka3	nie uczymy sie ale jest fajnie	5
\.


--
-- Data for Name: hobby; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.hobby (id, name) FROM stdin;
1	Anime
2	Manga
3	J-pop
4	J-rock
5	JLPT
6	Kuchnia
7	Kaligrafia
8	Cosplay
9	Dramy
10	Gry
11	Technologia
12	Podróże do Japonii
13	Kultura
14	Japońskie kino
15	Taniec
16	Fotografia
17	Literatura
\.


--
-- Data for Name: lesson; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lesson (uid, datetime, duration, type, "studentId", "teacherId") FROM stdin;
1e6c8d44-6618-452c-a1d7-13725822f46c	2025-09-17 06:44:04	60	2	1	3
46bdc839-321a-468d-8155-94f9ff3ada3a	2025-09-28 06:44:25	60	2	1	3
2b75e3a8-0597-4a26-ae80-65b50f7f5231	2025-09-25 06:44:20	60	4	1	3
23a8630b-bc68-47db-a531-318fe6db5892	2025-10-30 06:45:29	60	2	1	3
282a87cb-4aba-451c-b830-ac578856280e	2025-10-14 06:44:42	60	2	1	3
1ff36e9d-a2e4-4b29-8ee5-a2925d18e51f	2025-10-02 06:44:32	60	4	1	3
28d3f3f0-0ae7-4727-b62a-5eb896779db2	2025-09-14 06:43:55	60	4	1	3
628d6530-5f26-4472-a2cb-9ae86ea778a8	2025-10-20 06:45:04	60	2	1	3
f4956d2a-f397-468b-ae23-30a377e73d49	2025-09-10 06:43:52	60	2	1	3
83b6f74a-1442-4e67-ab00-df1cf6d48866	2025-10-26 06:45:20	60	4	1	3
1f20abab-7f24-4fdf-81a7-ac32f2bb637f	2025-10-17 06:44:46	60	4	1	3
\.


--
-- Data for Name: lessonType; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."lessonType" (id, name) FROM stdin;
1	Dla początkujących
2	Konwersacyjny
3	Dla dzieci
4	JLPT
5	Japoński w biznesie
6	Intensywny kurs
\.


--
-- Data for Name: note; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.note (id, "studentId", content) FROM stdin;
\.


--
-- Data for Name: student; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.student (id, "userUid", birthdate) FROM stdin;
1	bcd98020-8886-4b22-88d3-d5a39db2de23	1994-09-10 06:34:06
2	961aa500-c4b2-4b80-b0e0-abd34b5de5f9	2001-09-10 06:34:19
3	5bde7e1a-6bcf-49cb-adbf-c8294a52b07e	1994-12-10 06:34:27
4	8f83cdd7-9fe1-4a24-9132-d47f10558af3	1984-06-16 06:34:43
5	230ff063-a338-442d-a3fa-c45de463605c	2006-08-30 06:34:57
6	143ebbb9-eefb-4810-ab87-760c163b7c1f	2008-09-10 06:35:11
7	48769342-4425-4ec0-9733-982c428f62f3	2006-11-10 06:35:23
8	b71edc06-91ba-4f6d-b51d-4fbf736e19ca	1999-09-10 06:35:32
9	838f3bf7-e321-4abe-b21c-09a90a053a3a	1999-09-10 06:35:41
10	88f20474-234c-41d1-9e98-97b35c1ee91b	1976-09-19 06:35:47
11	b0944df3-332c-4eb2-89b6-3828a8ccd6c4	1985-09-11 06:35:55
\.


--
-- Data for Name: studentGroup; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."studentGroup" ("studentId", "groupUid") FROM stdin;
2	67d9e108-03b0-400a-87e7-649bd04d648a
\.


--
-- Data for Name: studentHobby; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."studentHobby" ("studentId", "hobbyId") FROM stdin;
\.


--
-- Data for Name: teacher; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.teacher (id, "userUid") FROM stdin;
1	278ce718-c40c-485f-a45f-64c5dd1ac6e9
2	04909149-2a95-4f2e-b89c-20c60d88799b
3	f2da234e-963a-4793-9537-0582d7bb9f3f
4	90bec9c1-dc27-4213-8581-8e411ed74a4f
\.


--
-- Data for Name: teacherLesson; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."teacherLesson" ("teacherId", "lessonTypeId") FROM stdin;
1	2
2	2
3	2
4	2
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."user" (uid, email, password, description, photo, name, surname, level) FROM stdin;
8f83cdd7-9fe1-4a24-9132-d47f10558af3	dominik.mazur64@gmail.com	1	Uczę się japońskiego od roku, interesuję się kulturą i anime.	\N	Dominik	Mazur	N3
88f20474-234c-41d1-9e98-97b35c1ee91b	marek.wisniewski88@gmail.com	1	Potrzebuję wsparcia w pisaniu esejów i ćwiczeniu formalnych zwrotów.	\N	Marek	Wiśniewski	N4
48769342-4425-4ec0-9733-982c428f62f3	natalia.zielinska95@gmail.com	1	lubie placki	\N	Natalia	Zielińska	N4
bcd98020-8886-4b22-88d3-d5a39db2de23	adam@gmail.com	$2y$12$ezINFb/IwqnQ.v6qHZG6puTJPEv5UbAgkhVX3vrrd0WDbnK9VkJyu	Chciałbym ćwiczyć czytanie artykułów i gazet po japońsku.	\N	Adam	Nowicki	N3
278ce718-c40c-485f-a45f-64c5dd1ac6e9	sara.jablonska19@gmail.com	1	Uczę języka biznesowego i etykiety w pracy w Japonii.	\N	Sara	Jabłońska	N1
5bde7e1a-6bcf-49cb-adbf-c8294a52b07e	monika.krawczyk90@gmail.com	1	Chcę znaleźć partnera do rozmów online, by poprawić płynność.	\N	Monika	Krawczyk	N3
b71edc06-91ba-4f6d-b51d-4fbf736e19ca	piotr.kaczmarek77@gmail.com	1	Uczę się dla przyjemności, interesuje mnie muzyka J-pop i podróże.	\N	Piotr	Kaczmarek	N5
230ff063-a338-442d-a3fa-c45de463605c	michal.kowalczyk33@gmail.com	1	Regularnie powtarzam słownictwo i szukam kogoś do ćwiczenia dialogów.	\N	Michał	Kowalczyk	N5
04909149-2a95-4f2e-b89c-20c60d88799b	keiko.yamada88@gmail.com	1	Od 10 lat uczę japońskiego studentów zagranicznych, skupiam się na naturalnej wymowie.	\N	Keiko	Yamada	N1
961aa500-c4b2-4b80-b0e0-abd34b5de5f9	karolina.malinowska12@gmail.com	1	Zależy mi na krótkich, codziennych rozmowach dla lepszej wymowy.	\N	Karolina	Malinowska	N2
143ebbb9-eefb-4810-ab87-760c163b7c1f	tomek.lewandowski21@gmail.com	1	Przygotowuję się do poziomu N3 i szukam osoby do wspólnej nauki gramatyki.	\N	Tomek	Lewandowski	N3
b0944df3-332c-4eb2-89b6-3828a8ccd6c4	anna.kowalska92@gmail.com	1	Chcę utrzymać regularność nauki i znaleźć partnera do wspólnej motywacji.	\N	Anna	Kowalska	N5
838f3bf7-e321-4abe-b21c-09a90a053a3a	julia.nowak07@gmail.com	1	Mam doświadczenie w tłumaczeniach i chcę ćwiczyć szybkie reagowanie w dialogach.	\N	Julia	Nowak	N3
90bec9c1-dc27-4213-8581-8e411ed74a4f	yuki.saito23@gmail.com	$2y$12$ezINFb/IwqnQ.v6qHZG6puTJPEv5UbAgkhVX3vrrd0WDbnK9VkJyu	Moim celem jest przekazywanie wiedzy o gramatyce i kulturze poprzez praktyczne przykłady.	\N	Yuki	Saito	N1
f2da234e-963a-4793-9537-0582d7bb9f3f	hiroshi.tanaka55@gmail.com	$2y$12$ezINFb/IwqnQ.v6qHZG6puTJPEv5UbAgkhVX3vrrd0WDbnK9VkJyu	Specjalizuję się w konwersacjach i przygotowaniu do JLPT.	\N	Hiroshi	Tanaka	N1
\.


--
-- Name: hobby_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.hobby_id_seq', 17, true);


--
-- Name: lessonType_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."lessonType_id_seq"', 6, true);


--
-- Name: note_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.note_id_seq', 1, false);


--
-- Name: student_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.student_id_seq', 11, true);


--
-- Name: teacher_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.teacher_id_seq', 4, true);


--
-- Name: group group_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."group"
    ADD CONSTRAINT group_pkey PRIMARY KEY (uid);


--
-- Name: hobby hobby_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hobby
    ADD CONSTRAINT hobby_pkey PRIMARY KEY (id);


--
-- Name: lessonType lessonType_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."lessonType"
    ADD CONSTRAINT "lessonType_pkey" PRIMARY KEY (id);


--
-- Name: lesson lesson_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lesson
    ADD CONSTRAINT lesson_pkey PRIMARY KEY (uid);


--
-- Name: note note_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.note
    ADD CONSTRAINT note_pkey PRIMARY KEY (id);


--
-- Name: studentGroup pk_studentGroup; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."studentGroup"
    ADD CONSTRAINT "pk_studentGroup" PRIMARY KEY ("studentId", "groupUid");


--
-- Name: studentHobby pk_studentHobby; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."studentHobby"
    ADD CONSTRAINT "pk_studentHobby" PRIMARY KEY ("studentId", "hobbyId");


--
-- Name: teacherLesson pk_teacherLesson; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."teacherLesson"
    ADD CONSTRAINT "pk_teacherLesson" PRIMARY KEY ("teacherId", "lessonTypeId");


--
-- Name: student student_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.student
    ADD CONSTRAINT student_pkey PRIMARY KEY (id);


--
-- Name: student student_userUid_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.student
    ADD CONSTRAINT "student_userUid_key" UNIQUE ("userUid");


--
-- Name: teacher teacher_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teacher
    ADD CONSTRAINT teacher_pkey PRIMARY KEY (id);


--
-- Name: teacher teacher_userUid_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teacher
    ADD CONSTRAINT "teacher_userUid_key" UNIQUE ("userUid");


--
-- Name: user user_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_email_key UNIQUE (email);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (uid);


--
-- Name: lesson lesson_studentId_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lesson
    ADD CONSTRAINT "lesson_studentId_fkey" FOREIGN KEY ("studentId") REFERENCES public.student(id) ON DELETE CASCADE;


--
-- Name: lesson lesson_teacherId_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lesson
    ADD CONSTRAINT "lesson_teacherId_fkey" FOREIGN KEY ("teacherId") REFERENCES public.teacher(id) ON DELETE CASCADE;


--
-- Name: lesson lesson_type_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lesson
    ADD CONSTRAINT lesson_type_fkey FOREIGN KEY (type) REFERENCES public."lessonType"(id) ON DELETE CASCADE;


--
-- Name: note note_studentId_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.note
    ADD CONSTRAINT "note_studentId_fkey" FOREIGN KEY ("studentId") REFERENCES public.student(id) ON DELETE CASCADE;


--
-- Name: studentGroup studentGroup_groupUid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."studentGroup"
    ADD CONSTRAINT "studentGroup_groupUid_fkey" FOREIGN KEY ("groupUid") REFERENCES public."group"(uid) ON DELETE CASCADE;


--
-- Name: studentGroup studentGroup_studentId_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."studentGroup"
    ADD CONSTRAINT "studentGroup_studentId_fkey" FOREIGN KEY ("studentId") REFERENCES public.student(id) ON DELETE CASCADE;


--
-- Name: studentHobby studentHobby_hobbyId_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."studentHobby"
    ADD CONSTRAINT "studentHobby_hobbyId_fkey" FOREIGN KEY ("hobbyId") REFERENCES public.hobby(id) ON DELETE CASCADE;


--
-- Name: studentHobby studentHobby_studentId_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."studentHobby"
    ADD CONSTRAINT "studentHobby_studentId_fkey" FOREIGN KEY ("studentId") REFERENCES public.student(id) ON DELETE CASCADE;


--
-- Name: student student_userUid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.student
    ADD CONSTRAINT "student_userUid_fkey" FOREIGN KEY ("userUid") REFERENCES public."user"(uid) ON DELETE CASCADE;


--
-- Name: teacherLesson teacherLesson_lessonTypeId_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."teacherLesson"
    ADD CONSTRAINT "teacherLesson_lessonTypeId_fkey" FOREIGN KEY ("lessonTypeId") REFERENCES public."lessonType"(id) ON DELETE CASCADE;


--
-- Name: teacherLesson teacherLesson_teacherId_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."teacherLesson"
    ADD CONSTRAINT "teacherLesson_teacherId_fkey" FOREIGN KEY ("teacherId") REFERENCES public.teacher(id) ON DELETE CASCADE;


--
-- Name: teacher teacher_userUid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.teacher
    ADD CONSTRAINT "teacher_userUid_fkey" FOREIGN KEY ("userUid") REFERENCES public."user"(uid) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--