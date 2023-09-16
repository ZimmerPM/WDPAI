--
-- PostgreSQL database dump
--

-- Dumped from database version 15.4 (Debian 15.4-1.pgdg120+1)
-- Dumped by pg_dump version 15.4 (Debian 15.4-1.pgdg120+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: uuid-ossp; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;


--
-- Name: EXTENSION "uuid-ossp"; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: authors; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.authors (
    id integer NOT NULL,
    name character varying(255)
);


ALTER TABLE public.authors OWNER TO zimmer;

--
-- Name: authors_id_seq; Type: SEQUENCE; Schema: public; Owner: zimmer
--

CREATE SEQUENCE public.authors_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.authors_id_seq OWNER TO zimmer;

--
-- Name: authors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: zimmer
--

ALTER SEQUENCE public.authors_id_seq OWNED BY public.authors.id;


--
-- Name: book_copies; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.book_copies (
    copy_id integer NOT NULL,
    book_id integer
);


ALTER TABLE public.book_copies OWNER TO zimmer;

--
-- Name: book_copies_copy_id_seq; Type: SEQUENCE; Schema: public; Owner: zimmer
--

CREATE SEQUENCE public.book_copies_copy_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.book_copies_copy_id_seq OWNER TO zimmer;

--
-- Name: book_copies_copy_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: zimmer
--

ALTER SEQUENCE public.book_copies_copy_id_seq OWNED BY public.book_copies.copy_id;


--
-- Name: books; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.books (
    id integer NOT NULL,
    title character varying(255),
    publicationyear integer,
    genre character varying(100),
    availability boolean DEFAULT true,
    stock integer,
    image character varying(255)
);


ALTER TABLE public.books OWNER TO zimmer;

--
-- Name: books_id_seq; Type: SEQUENCE; Schema: public; Owner: zimmer
--

CREATE SEQUENCE public.books_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.books_id_seq OWNER TO zimmer;

--
-- Name: books_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: zimmer
--

ALTER SEQUENCE public.books_id_seq OWNED BY public.books.id;


--
-- Name: booksauthors; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.booksauthors (
    book_id integer NOT NULL,
    author_id integer NOT NULL
);


ALTER TABLE public.booksauthors OWNER TO zimmer;

--
-- Name: borrowedbooks; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.borrowedbooks (
    id integer NOT NULL,
    user_id integer,
    book_id integer,
    borrowed_date date,
    return_date date
);


ALTER TABLE public.borrowedbooks OWNER TO zimmer;

--
-- Name: borrowedbooks_id_seq; Type: SEQUENCE; Schema: public; Owner: zimmer
--

CREATE SEQUENCE public.borrowedbooks_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.borrowedbooks_id_seq OWNER TO zimmer;

--
-- Name: borrowedbooks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: zimmer
--

ALTER SEQUENCE public.borrowedbooks_id_seq OWNED BY public.borrowedbooks.id;


--
-- Name: reservedbooks; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.reservedbooks (
    id integer NOT NULL,
    user_id integer,
    book_id integer,
    reserved_date date
);


ALTER TABLE public.reservedbooks OWNER TO zimmer;

--
-- Name: reservedbooks_id_seq; Type: SEQUENCE; Schema: public; Owner: zimmer
--

CREATE SEQUENCE public.reservedbooks_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.reservedbooks_id_seq OWNER TO zimmer;

--
-- Name: reservedbooks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: zimmer
--

ALTER SEQUENCE public.reservedbooks_id_seq OWNED BY public.reservedbooks.id;


--
-- Name: userdetails; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.userdetails (
    id integer NOT NULL,
    name character varying NOT NULL,
    lastname character varying NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.userdetails OWNER TO zimmer;

--
-- Name: userdetails_id_seq; Type: SEQUENCE; Schema: public; Owner: zimmer
--

CREATE SEQUENCE public.userdetails_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.userdetails_id_seq OWNER TO zimmer;

--
-- Name: userdetails_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: zimmer
--

ALTER SEQUENCE public.userdetails_id_seq OWNED BY public.userdetails.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.users (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(255) DEFAULT 'user'::character varying NOT NULL
);


ALTER TABLE public.users OWNER TO zimmer;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: zimmer
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO zimmer;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: zimmer
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: authors id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.authors ALTER COLUMN id SET DEFAULT nextval('public.authors_id_seq'::regclass);


--
-- Name: book_copies copy_id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.book_copies ALTER COLUMN copy_id SET DEFAULT nextval('public.book_copies_copy_id_seq'::regclass);


--
-- Name: books id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.books ALTER COLUMN id SET DEFAULT nextval('public.books_id_seq'::regclass);


--
-- Name: borrowedbooks id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.borrowedbooks ALTER COLUMN id SET DEFAULT nextval('public.borrowedbooks_id_seq'::regclass);


--
-- Name: reservedbooks id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.reservedbooks ALTER COLUMN id SET DEFAULT nextval('public.reservedbooks_id_seq'::regclass);


--
-- Name: userdetails id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.userdetails ALTER COLUMN id SET DEFAULT nextval('public.userdetails_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: authors; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.authors (id, name) FROM stdin;
1	Markus Zusak
2	Gabriel Garc├şa M├írquez
3	Stephen King
4	J.K. Rowling
5	George Orwell
6	Paulo Coelho
7	Jane Austen
8	Agatha Christie
9	Michai┼é Bu┼éhakow
10	J.R.R. Tolkien
11	Fiodor Dostojewski
12	Philip K. Dick
13	Hanya Yanagihara
14	Ryszard Kapu┼Ťci┼äski
15	Helen Fielding
16	Jordan Belfort
17	Jakub ┼╗ulczyk
18	B.A. Paris
19	George R.R. Martin
20	Jacek Dukaj
21	Anna Gavalda
22	Aldous Huxley
23	Carlos Ruiz Zaf├│n
24	Neil Gaiman
\.


--
-- Data for Name: book_copies; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.book_copies (copy_id, book_id) FROM stdin;
1	1
2	1
3	1
4	1
5	1
6	1
7	1
8	1
9	1
10	1
11	2
12	2
13	2
14	2
15	2
16	2
17	2
18	3
19	3
20	3
21	3
22	3
23	4
24	4
25	4
26	4
27	4
28	4
29	4
30	4
31	4
32	4
33	4
34	4
35	4
36	4
37	4
38	5
39	5
40	5
41	5
42	5
43	5
44	5
45	5
46	6
47	6
48	6
49	6
50	6
51	6
52	6
53	6
54	6
55	7
56	7
57	7
58	7
59	7
60	7
61	8
62	8
63	8
64	8
65	8
66	9
67	9
68	9
69	9
70	9
71	9
72	9
73	10
74	10
75	10
76	10
77	10
78	10
79	10
80	10
81	10
82	10
83	10
84	10
85	11
86	11
87	11
88	11
89	11
90	11
91	12
92	12
93	12
94	12
95	12
96	13
97	13
98	13
99	13
100	13
101	13
102	13
103	13
104	13
105	13
106	13
107	13
108	13
109	13
110	14
111	14
112	14
113	14
114	14
115	14
116	14
117	15
118	15
119	15
120	15
121	16
122	16
123	16
124	16
125	16
126	16
127	16
128	16
129	16
130	17
131	17
132	17
133	17
134	17
135	17
136	17
137	17
138	18
139	18
140	18
141	18
142	18
143	18
144	18
145	19
146	19
147	19
148	19
149	19
150	19
151	19
152	19
153	19
154	19
155	19
156	20
157	20
158	20
159	20
160	20
161	20
162	21
163	21
164	21
165	21
166	21
167	21
168	21
169	21
170	21
171	21
172	22
173	22
174	22
175	22
176	22
177	22
178	23
179	23
180	23
181	23
182	23
183	23
184	24
185	24
186	25
187	25
188	25
189	26
190	26
\.


--
-- Data for Name: books; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.books (id, title, publicationyear, genre, availability, stock, image) FROM stdin;
1	Z┼éodziejka ksi─ů┼╝ek	2005	Powie┼Ť─ç historyczna	t	10	public/uploads/zlodziejka.jpg
2	Sto lat samotno┼Ťci	1967	Realizm magiczny	t	7	public/uploads/100LS.jpg
3	To	1986	Horror	t	5	public/uploads/to.jpg
4	Harry Potter i Kamie┼ä Filozoficzny	1997	Fantastyka	t	15	public/uploads/kamien.jpg
5	Rok 1984	1949	Dystopia	t	8	public/uploads/rok1984.jpg
6	Alchemik	1988	Przygodowy	t	9	public/uploads/alchemik.jpg
7	Duma i uprzedzenie	1813	Romans	t	6	public/uploads/diu.jpg
8	Zab├│jstwo Rogera Ackroyda	1926	Krymina┼é	t	5	public/uploads/zab.jpg
9	Mistrz i Ma┼égorzata	1967	Powie┼Ť─ç	t	7	public/uploads/MiM.jpg
10	W┼éadca Pier┼Ťcieni: Dru┼╝yna Pier┼Ťcienia	1954	Fantastyka	t	12	public/uploads/wladca-ring.jpg
11	Zbrodnia i kara	1866	Krymina┼é	t	6	public/uploads/zik.jpg
12	Cz┼éowiek z wysokiego zamku	1962	Alternatywna historia	t	5	public/uploads/zamek.jpg
13	Harry Potter i Insygnia ┼Ümierci	2007	Fantastyka	t	14	public/uploads/insygnia.jpg
14	Ma┼ée ┼╝ycie	2015	Dramat	t	7	public/uploads/malezycie.jpg
15	Imperium	1992	Literatura faktu	t	4	public/uploads/imperium.jpg
16	Dziennik Bridget Jones	1996	Komedia	t	9	public/uploads/bridget.jpg
17	Wilk z Wall Street	2007	Autobiografia	t	8	public/uploads/wallstreet.jpg
18	┼Ülepn─ůc od ┼Ťwiate┼é	2014	Krymina┼é	t	7	public/uploads/slepnac.jpg
19	Gra o tron	1996	Fantastyka	t	11	public/uploads/tron.jpg
20	Skazani na Shawshank	1982	Dramat	t	6	public/uploads/shawshank.jpg
21	W┼éadca Pier┼Ťcieni: Powr├│t Kr├│la	1955	Fantastyka	t	10	public/uploads/wladca-king.jpg
22	Katedra	2000	Science-fiction	t	6	public/uploads/katedra.jpg
23	Po prostu razem	2000	Powie┼Ť─ç	t	6	public/uploads/razem.jpg
24	Nowy wspania┼éy ┼Ťwiat	1932	Dystopia	t	2	public/uploads/bnw.jpg
25	Cie┼ä wiatru	2001	Krymina┼é historyczny	t	3	public/uploads/shadow.jpg
26	Ameryka┼äscy bogowie	2001	Fantastyka	t	2	public/uploads/bogowie.jpg
\.


--
-- Data for Name: booksauthors; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.booksauthors (book_id, author_id) FROM stdin;
1	1
2	2
3	3
4	4
5	5
6	6
7	7
8	8
9	9
10	10
11	11
12	12
13	4
14	13
15	14
16	15
17	16
18	17
19	19
20	3
21	10
22	20
23	21
24	22
25	23
26	24
\.


--
-- Data for Name: borrowedbooks; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.borrowedbooks (id, user_id, book_id, borrowed_date, return_date) FROM stdin;
\.


--
-- Data for Name: reservedbooks; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.reservedbooks (id, user_id, book_id, reserved_date) FROM stdin;
\.


--
-- Data for Name: userdetails; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.userdetails (id, name, lastname, user_id) FROM stdin;
1	Jan	Kowalski	3
2	Anna	Kowalska	4
3	Jan	Nowak	5
4	Marta	Sienkiewicz	6
5	Piotr	Malinowski	7
6	El┼╝bieta	Jankowska	8
7	admin	admin	10
8	Adam	Skotnicki	11
9	Tomasz	Nowacki	12
12	Piotr	Zimirski	15
14	Marek	Markowski	17
15	Piotr	Zimirski	18
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.users (id, email, password, role) FROM stdin;
3	jkowalski@post.com	$2y$10$qJBAAfMHBHGhOsb2Pbve4ON.eJZ08Ibl.O.PfyyfDHh8aJXqSz1E6	user
4	anna.kowalska@example.com	$2y$10$1VlCoTgqXdiA3rR.k7ftIeYla1RqcDnlT3wYmJqNErWyV1MNxVkzG	user
5	jan.nowak@example.com	$2y$10$akzeiNwKZNy9xOFdS6jdQ.5Qn/0ybScSPtq9nffel3N/zo4TuBOwm	user
6	marta.sienkiewicz@example.com	$2y$10$buaARxn5eRMgwWZ9dwqkIuuA//IEYu77eVPwAeH5S32TPg8HrAeCG	user
7	piotr.malinowski@example.com	$2y$10$uA9XLHWOZ5Lwe0FnfVpRWu05LwZ3XfaybeRBFCAKkBAPGY.2YmDcC	user
8	elzbieta.jankowska@example.com	$2y$10$QsDIeY84hY5HtAMHSKZabucEYryPTEHhWRzfPu587zymSWB4VA6a2	user
10	admin@admin.com	$2y$10$CBXnwj6.JQ4Jcms3X4b.keVps5.eNvwzLrrtj1OZAMQxwK0H.UCAu	admin
11	askotnicki@mail.com	$2y$10$nvdsOtwwuhn0MpKPW9G9auKrARhg/WQffQi4ypaJbunasaYIk3Oqa	user
12	tomasz@poczta.pl	$2y$10$7vBbJRiyPDpKGZOB/1Ma0eWT8y53VjU9jB3.rbvA6JPyATJui1hBu	user
17	marek@marek.com	$2y$10$ViSOhCVDSsq61ydb7lOI8eBzN8E4sSmkn/cEPQX7BAMQ7F9mNLVaO	user
18	zimmer@poczta.com	$2y$10$TjO.5HOD46Txxjsko4cumebgpqWD5EwtkdI7aOovuviUSjyBnZ8NG	user
15	zimmer@mail.com	$2y$10$IQIhCjftFlYwZQm0cIZzOeL.dVGmXbQtXkUT/DZma0IDNehAc6ZFq	admin
\.


--
-- Name: authors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.authors_id_seq', 24, true);


--
-- Name: book_copies_copy_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.book_copies_copy_id_seq', 190, true);


--
-- Name: books_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.books_id_seq', 26, true);


--
-- Name: borrowedbooks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.borrowedbooks_id_seq', 1, false);


--
-- Name: reservedbooks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.reservedbooks_id_seq', 1, false);


--
-- Name: userdetails_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.userdetails_id_seq', 15, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.users_id_seq', 18, true);


--
-- Name: authors authors_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.authors
    ADD CONSTRAINT authors_pkey PRIMARY KEY (id);


--
-- Name: book_copies book_copies_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.book_copies
    ADD CONSTRAINT book_copies_pkey PRIMARY KEY (copy_id);


--
-- Name: books books_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_pkey PRIMARY KEY (id);


--
-- Name: booksauthors booksauthors_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.booksauthors
    ADD CONSTRAINT booksauthors_pkey PRIMARY KEY (book_id, author_id);


--
-- Name: borrowedbooks borrowedbooks_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.borrowedbooks
    ADD CONSTRAINT borrowedbooks_pkey PRIMARY KEY (id);


--
-- Name: reservedbooks reservedbooks_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.reservedbooks
    ADD CONSTRAINT reservedbooks_pkey PRIMARY KEY (id);


--
-- Name: userdetails userdetails_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.userdetails
    ADD CONSTRAINT userdetails_pkey PRIMARY KEY (id);


--
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: book_copies book_copies_book_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.book_copies
    ADD CONSTRAINT book_copies_book_id_fkey FOREIGN KEY (book_id) REFERENCES public.books(id);


--
-- Name: booksauthors booksauthors_author_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.booksauthors
    ADD CONSTRAINT booksauthors_author_id_fkey FOREIGN KEY (author_id) REFERENCES public.authors(id);


--
-- Name: booksauthors booksauthors_book_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.booksauthors
    ADD CONSTRAINT booksauthors_book_id_fkey FOREIGN KEY (book_id) REFERENCES public.books(id);


--
-- Name: borrowedbooks borrowedbooks_book_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.borrowedbooks
    ADD CONSTRAINT borrowedbooks_book_id_fkey FOREIGN KEY (book_id) REFERENCES public.books(id);


--
-- Name: borrowedbooks borrowedbooks_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.borrowedbooks
    ADD CONSTRAINT borrowedbooks_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: reservedbooks reservedbooks_book_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.reservedbooks
    ADD CONSTRAINT reservedbooks_book_id_fkey FOREIGN KEY (book_id) REFERENCES public.books(id);


--
-- Name: reservedbooks reservedbooks_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.reservedbooks
    ADD CONSTRAINT reservedbooks_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: userdetails userdetails_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.userdetails
    ADD CONSTRAINT userdetails_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- PostgreSQL database dump complete
--

