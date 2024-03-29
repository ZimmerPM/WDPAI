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


--
-- Name: create_book_copies(); Type: FUNCTION; Schema: public; Owner: zimmer
--

CREATE FUNCTION public.create_book_copies() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Dla ka┼╝dego egzemplarza ksi─ů┼╝ki, dodaj nowy rekord w tabeli book_copies.
    FOR i IN 1..(SELECT stock FROM books WHERE id = NEW.book_id) LOOP
            INSERT INTO book_copies (book_id, status) VALUES (NEW.book_id, 'available');
        END LOOP;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public.create_book_copies() OWNER TO zimmer;

--
-- Name: delete_book_copies(); Type: FUNCTION; Schema: public; Owner: zimmer
--

CREATE FUNCTION public.delete_book_copies() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Usu┼ä wszystkie egzemplarze ksi─ů┼╝ki z tabeli book_copies dla danego book_id.
    DELETE FROM book_copies WHERE book_id = OLD.book_id;
    RETURN OLD;
END;
$$;


ALTER FUNCTION public.delete_book_copies() OWNER TO zimmer;

--
-- Name: update_availability(); Type: FUNCTION; Schema: public; Owner: zimmer
--

CREATE FUNCTION public.update_availability() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF NEW.stock = 0 THEN
        NEW.availability := 'false';
    ELSIF NEW.stock > 0 THEN
        NEW.availability := 'true';
    END IF;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public.update_availability() OWNER TO zimmer;

--
-- Name: update_book_stock(); Type: FUNCTION; Schema: public; Owner: zimmer
--

CREATE FUNCTION public.update_book_stock() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Aktualizuje warto┼Ť─ç stock w tabeli books na podstawie ilo┼Ťci dost─Öpnych egzemplarzy w tabeli book_copies.
    UPDATE books
    SET stock = (SELECT COUNT(*) FROM book_copies WHERE book_id = NEW.book_id AND status = 'available')
    WHERE id = NEW.book_id;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public.update_book_stock() OWNER TO zimmer;

--
-- Name: update_stock(); Type: FUNCTION; Schema: public; Owner: zimmer
--

CREATE FUNCTION public.update_stock() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF TG_OP = 'DELETE' THEN
        UPDATE books SET stock = stock - 1 WHERE id = OLD.book_id;
    ELSIF TG_OP = 'INSERT' THEN
        UPDATE books SET stock = stock + 1 WHERE id = NEW.book_id;
    END IF;
    RETURN NULL;
END;
$$;


ALTER FUNCTION public.update_stock() OWNER TO zimmer;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: archive_loans; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.archive_loans (
    id integer NOT NULL,
    user_id integer,
    copy_id integer,
    borrowed_date date,
    expected_return_date date,
    actual_return_date date
);


ALTER TABLE public.archive_loans OWNER TO zimmer;

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
    id integer NOT NULL,
    book_id integer,
    status character varying(50),
    CONSTRAINT status_check CHECK (((status)::text = ANY ((ARRAY['available'::character varying, 'reserved'::character varying, 'borrowed'::character varying])::text[])))
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

ALTER SEQUENCE public.book_copies_copy_id_seq OWNED BY public.book_copies.id;


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
-- Name: books_authors; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.books_authors (
    book_id integer NOT NULL,
    author_id integer NOT NULL
);


ALTER TABLE public.books_authors OWNER TO zimmer;

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
-- Name: borrowed_books; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.borrowed_books (
    id integer NOT NULL,
    user_id integer,
    copy_id integer,
    borrowed_date date,
    expected_return_date date,
    actual_return_date date
);


ALTER TABLE public.borrowed_books OWNER TO zimmer;

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

ALTER SEQUENCE public.borrowedbooks_id_seq OWNED BY public.borrowed_books.id;


--
-- Name: reserved_books; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.reserved_books (
    id integer NOT NULL,
    user_id integer,
    copy_id integer,
    reservation_date date,
    reservation_end date
);


ALTER TABLE public.reserved_books OWNER TO zimmer;

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

ALTER SEQUENCE public.reservedbooks_id_seq OWNED BY public.reserved_books.id;


--
-- Name: user_details; Type: TABLE; Schema: public; Owner: zimmer
--

CREATE TABLE public.user_details (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    lastname character varying(255) NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.user_details OWNER TO zimmer;

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

ALTER SEQUENCE public.userdetails_id_seq OWNED BY public.user_details.id;


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
-- Name: book_copies id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.book_copies ALTER COLUMN id SET DEFAULT nextval('public.book_copies_copy_id_seq'::regclass);


--
-- Name: books id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.books ALTER COLUMN id SET DEFAULT nextval('public.books_id_seq'::regclass);


--
-- Name: borrowed_books id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.borrowed_books ALTER COLUMN id SET DEFAULT nextval('public.borrowedbooks_id_seq'::regclass);


--
-- Name: reserved_books id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.reserved_books ALTER COLUMN id SET DEFAULT nextval('public.reservedbooks_id_seq'::regclass);


--
-- Name: user_details id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.user_details ALTER COLUMN id SET DEFAULT nextval('public.userdetails_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: archive_loans; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.archive_loans (id, user_id, copy_id, borrowed_date, expected_return_date, actual_return_date) FROM stdin;
25	22	392	2023-09-23	2023-10-23	2023-09-24
39	6	209	2023-09-23	2023-10-23	2023-09-24
35	6	194	2023-09-23	2023-10-23	2023-09-24
37	6	264	2023-09-23	2023-10-23	2023-09-24
55	21	181	2023-09-24	2023-10-24	2023-09-24
41	6	191	2023-09-24	2023-10-24	2023-09-24
49	6	198	2023-09-24	2023-10-24	2023-09-24
44	6	390	2023-09-24	2023-10-24	2023-09-24
56	21	56	2023-09-24	2023-10-24	2023-09-24
51	6	187	2023-09-24	2023-10-24	2023-09-24
42	6	234	2023-09-24	2023-10-24	2023-09-24
52	21	337	2023-09-24	2023-10-24	2023-09-24
40	6	414	2023-09-24	2023-10-24	2023-09-24
60	6	147	2023-09-24	2023-10-24	2023-09-24
57	21	63	2023-09-24	2023-10-24	2023-09-24
58	21	240	2023-09-24	2023-10-24	2023-09-24
53	21	193	2023-09-24	2023-10-24	2023-09-24
62	22	192	2023-09-24	2023-10-24	2023-09-24
63	6	11	2023-09-24	2023-10-24	2023-09-25
66	6	38	2023-09-24	2023-10-24	2023-09-25
86	21	193	2023-09-24	2023-10-24	2023-09-25
92	6	192	2023-09-25	2023-10-25	2023-09-25
\.


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
25	Adam Mickiewicz
26	Aleksander Dumas
27	Anthony Doerr
28	J─Ödrzej Pasierski
29	Joanna Chmielewska
30	Boles┼éaw Prus
35	Antoine de Saint-Exup├ęry
34	Joseph Heller
33	Stanis┼éaw Lem
32	Karol Darwin
31	Marta Matyszczak
36	Darwin
37	Sue Monk Kidd
39	Janusz G┼éowacki
38	Leonie Swann
40	Joe Hill
41	Herman Hesse
42	Bartosz Or┼éowski
43	Henryk Sienkiewicz
44	Juliusz S┼éowacki
45	Franz Kafka
46	aaaa
47	erwe
\.


--
-- Data for Name: book_copies; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.book_copies (id, book_id, status) FROM stdin;
186	25	available
240	39	available
190	26	available
416	50	available
417	50	available
418	50	available
409	50	available
410	50	available
411	50	available
412	50	available
24	4	available
1	1	available
197	29	borrowed
203	31	borrowed
202	31	borrowed
199	29	borrowed
12	2	reserved
200	30	available
201	30	available
389	47	available
222	38	borrowed
326	43	borrowed
178	23	borrowed
11	2	available
205	32	available
206	32	available
207	32	available
208	32	available
18	3	borrowed
223	38	available
325	43	borrowed
38	5	available
215	36	available
216	36	available
217	36	available
218	36	available
219	36	available
220	37	available
384	46	available
387	46	available
388	46	available
265	42	available
328	43	available
329	44	available
330	44	available
331	44	available
332	44	available
333	44	available
334	44	available
335	44	available
336	44	available
385	46	available
27	4	available
28	4	available
2	1	available
3	1	available
4	1	available
5	1	available
195	29	reserved
193	27	available
192	27	available
6	1	available
7	1	available
8	1	available
9	1	available
10	1	available
189	26	available
213	36	available
209	33	available
188	25	available
264	42	available
13	2	available
14	2	available
15	2	available
16	2	available
210	33	available
198	29	available
204	31	borrowed
196	29	available
238	39	available
327	43	borrowed
239	39	available
413	50	available
390	47	available
211	34	borrowed
415	50	reserved
230	40	available
187	25	available
214	36	reserved
414	50	available
17	2	available
19	3	available
20	3	available
21	3	available
22	3	available
23	4	available
25	4	available
26	4	available
29	4	available
30	4	available
31	4	available
32	4	available
33	4	available
34	4	available
35	4	available
36	4	available
37	4	available
228	40	reserved
191	27	reserved
235	41	borrowed
393	48	available
394	48	available
395	48	available
396	48	available
397	48	available
398	48	available
399	49	available
400	49	available
401	49	available
402	49	available
403	49	available
404	49	available
405	49	available
406	49	available
407	49	available
408	49	available
194	28	reserved
221	37	available
224	38	available
225	38	available
226	38	available
227	38	available
232	40	available
229	40	available
391	48	available
231	40	available
233	40	available
236	41	available
237	41	available
338	45	available
339	45	available
262	42	available
263	42	available
212	35	available
392	48	available
234	41	available
337	45	available
64	8	borrowed
55	7	borrowed
65	8	borrowed
40	5	borrowed
39	5	borrowed
56	7	available
41	5	available
42	5	available
52	6	available
53	6	available
54	6	available
57	7	available
43	5	available
58	7	available
59	7	available
60	7	available
66	9	available
67	9	available
68	9	available
69	9	available
70	9	available
71	9	available
72	9	available
73	10	available
74	10	available
75	10	available
76	10	available
77	10	available
78	10	available
79	10	available
80	10	available
81	10	available
82	10	available
83	10	available
84	10	available
147	19	available
162	21	available
173	22	borrowed
110	14	borrowed
157	20	borrowed
184	24	borrowed
89	11	available
90	11	available
91	12	available
92	12	available
93	12	available
94	12	available
95	12	available
97	13	available
98	13	available
99	13	available
100	13	available
101	13	available
102	13	available
103	13	available
104	13	available
105	13	available
106	13	available
107	13	available
108	13	available
109	13	available
112	14	available
386	46	available
113	14	available
114	14	available
115	14	available
116	14	available
117	15	available
118	15	available
119	15	available
120	15	available
122	16	available
123	16	available
124	16	available
125	16	available
126	16	available
127	16	available
128	16	available
129	16	available
130	17	available
131	17	available
132	17	available
133	17	available
134	17	available
135	17	available
136	17	available
137	17	available
138	18	available
139	18	available
140	18	available
141	18	available
142	18	available
143	18	available
144	18	available
148	19	available
149	19	available
150	19	available
151	19	available
152	19	available
153	19	available
154	19	available
155	19	available
159	20	available
160	20	available
161	20	available
163	21	available
164	21	available
165	21	available
166	21	available
167	21	available
168	21	available
169	21	available
170	21	available
171	21	available
174	22	available
175	22	available
176	22	available
177	22	available
145	19	available
185	24	borrowed
179	23	available
85	11	borrowed
44	5	available
45	5	available
46	6	available
47	6	available
48	6	available
49	6	available
50	6	available
51	6	available
87	11	available
156	20	available
121	16	borrowed
86	11	available
181	23	available
96	13	available
180	23	available
146	19	available
63	8	borrowed
172	22	available
158	20	borrowed
182	23	reserved
111	14	reserved
183	23	available
88	11	available
61	8	borrowed
62	8	reserved
\.


--
-- Data for Name: books; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.books (id, title, publicationyear, genre, availability, stock, image) FROM stdin;
37	Paragraf 22	2019	Powie┼Ť─ç wojenna	t	2	public/uploads/paragraf.jpg
39	Nocny lot	2012	Powie┼Ť─ç psychologiczna	t	3	public/uploads/nocny-lot.jpg
1	Z┼éodziejka ksi─ů┼╝ek	2005	Powie┼Ť─ç historyczna	t	10	public/uploads/zlodziejka.jpg
2	Sto lat samotno┼Ťci	1967	Realizm magiczny	t	6	public/uploads/100LS.jpg
7	Duma i uprzedzenie	1813	Romans	t	5	public/uploads/diu.jpg
29	┼Üwiat┼éo, kt├│rego nie wida─ç	2022	Powie┼Ť─ç wojenna	t	2	public/uploads/light.jpg
3	To	1986	Horror	t	4	public/uploads/to.jpg
27	Dziady	2010	Dramat	t	2	public/uploads/dziady.jpeg
16	Dziennik Bridget Jones	1996	Komedia	t	8	public/uploads/bridget.jpg
4	Harry Potter i Kamie┼ä Filozoficzny	1997	Fantastyka	t	15	public/uploads/kamien.jpg
26	Ameryka┼äscy bogowie	2001	Fantastyka	t	2	public/uploads/bogowie.jpg
50	Proces	2014	Powie┼Ť─ç surrealistyczna	t	9	public/uploads/proces.jpg
48	Quo Vadis	2003	Powie┼Ť─ç historyczna	t	8	public/uploads/quo-vadis.jpg
33	Lesio	2017	Powie┼Ť─ç kryminalna	t	2	public/uploads/lesio.jpg
36	Cyberiada	2023	Science-fiction	t	6	public/uploads/cyberiada.jpg
30	Wodnik	2021	Thriller	t	2	public/uploads/wodnik.jpg
25	Cie┼ä wiatru	2001	Krymina┼é historyczny	t	3	public/uploads/shadow.jpg
32	Taka tragedia w Ta┼étach	2022	Powie┼Ť─ç kryminalna	t	4	public/uploads/talty.jpg
24	Nowy wspania┼éy ┼Ťwiat	1932	Dystopijka	f	0	public/uploads/dzerzi.jpg
6	Alchemik	1988	Przygodowy	t	9	public/uploads/alchemik.jpg
31	Lalka	2013	Powie┼Ť─ç spo┼éeczna	f	0	public/uploads/lalka.jpg
8	Zab├│jstwo Rogera Ackroyda	1926	Krymina┼é	f	0	public/uploads/zab.jpg
5	Rok 1984	1949	Dystopia	t	6	public/uploads/rok1984.jpg
43	Sekretne ┼╝ycie pszcz├│┼é	2021	Powie┼Ť─ç obyczajowa	t	1	public/uploads/bees.jpg
42	Opowie┼Ťci o Pilocie Pirxie	2012	Science-fiction	t	4	public/uploads/pirx.jpg
47	Opowiadania nie tylko wroc┼éawskie	2023	Zbi├│r opowiada┼ä	t	2	public/uploads/wroclaw.jpg
38	Folwark zwierz─Öcy	2022	Dystopia	t	5	public/uploads/folwark.jpg
40	Ma┼éy ksi─ů┼╝─Ö	2020	Ba┼Ť┼ä	t	5	public/uploads/911059-352x500.jpg
35	O powstawaniu gatunk├│w	2020	Monografia	t	1	public/uploads/darwin.jpg
49	Kordian	2021	Dramacik	t	10	public/uploads/kordian.jpg
45	Good night, D┼╝erzi	2010	Powie┼Ť─ç biograficzna	t	3	public/uploads/dzerzi.jpg
41	Solaris	2012	Science-fiction	t	3	public/uploads/solaris.jpg
28	Trzej muszkieterowie	2015	Powie┼Ť─ç p┼éaszcza i szpady	f	0	public/uploads/muszkieterowie.jpg
34	Faraon	2019	Powie┼Ť─ç historyczna	f	0	public/uploads/faraon.jpg
44	Triumf owiec	2011	Thriller	t	8	public/uploads/owce.jpg
46	Najpi─Ökniejsze opowiadania	2023	Zbi├│r opowiada┼ä	t	5	public/uploads/Najpiekniejsze-opowiadania,-Hesse-Hermann.jpg
14	Ma┼ée ┼╝ycie	2015	Dramat	t	5	public/uploads/malezycie.jpg
13	Harry Potter i Insygnia ┼Ümierci	2007	Fantastyka	t	14	public/uploads/insygnia.jpg
11	Zbrodnia i kara	1866	Krymina┼é	t	5	public/uploads/zik.jpg
9	Mistrz i Ma┼égorzata	1967	Powie┼Ť─ç	t	7	public/uploads/MiM.jpg
10	W┼éadca Pier┼Ťcieni: Dru┼╝yna Pier┼Ťcienia	1954	Fantastyka	t	12	public/uploads/wladca-ring.jpg
12	Cz┼éowiek z wysokiego zamku	1962	Alternatywna historia	t	5	public/uploads/zamek.jpg
15	Imperium	1992	Literatura faktu	t	4	public/uploads/imperium.jpg
21	W┼éadca Pier┼Ťcieni: Powr├│t Kr├│la	1955	Fantastyka	t	10	public/uploads/wladca-king.jpg
20	Skazani na Shawshank	1982	Dramat	t	4	public/uploads/shawshank.jpg
23	Po prostu razem	2000	Powie┼Ť─ç	t	4	public/uploads/razem.jpg
17	Wilk z Wall Street	2007	Autobiografia	t	8	public/uploads/wallstreet.jpg
19	Gra o tron	1996	Fantastyka	t	11	public/uploads/tron.jpg
22	Katedra	2000	Science-fiction	t	5	public/uploads/katedra.jpg
18	┼Ülepn─ůc od ┼Ťwiate┼é	2014	Krymina┼é	t	7	public/uploads/slepnac.jpg
\.


--
-- Data for Name: books_authors; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.books_authors (book_id, author_id) FROM stdin;
1	1
3	3
4	4
5	5
7	7
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
25	23
26	24
48	43
29	27
30	28
50	45
38	5
28	26
8	8
27	25
24	22
49	44
45	39
2	2
9	9
6	6
33	29
34	30
31	30
39	35
40	35
37	34
36	33
41	33
35	32
32	31
42	33
43	37
44	38
46	41
47	42
\.


--
-- Data for Name: borrowed_books; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.borrowed_books (id, user_id, copy_id, borrowed_date, expected_return_date, actual_return_date) FROM stdin;
64	6	64	2023-09-24	2023-10-24	\N
65	6	197	2023-09-24	2023-10-24	\N
67	6	202	2023-09-24	2023-10-24	\N
69	21	173	2023-09-24	2023-10-24	\N
70	22	110	2023-09-24	2023-10-24	\N
71	22	222	2023-09-24	2023-10-24	\N
72	22	157	2023-09-24	2023-10-24	\N
73	22	178	2023-09-24	2023-10-24	\N
74	22	55	2023-09-24	2023-10-24	\N
75	22	184	2023-09-24	2023-10-24	\N
76	22	185	2023-09-24	2023-10-24	\N
77	22	85	2023-09-24	2023-10-24	\N
78	22	18	2023-09-24	2023-10-24	\N
79	22	65	2023-09-24	2023-10-24	\N
80	22	121	2023-09-24	2023-10-24	\N
81	22	325	2023-09-24	2023-10-24	\N
82	6	204	2023-09-24	2023-10-24	\N
83	6	63	2023-09-24	2023-10-24	\N
84	21	158	2023-09-24	2023-10-24	\N
85	21	327	2023-09-24	2023-10-24	\N
87	6	203	2023-09-25	2023-10-25	\N
88	6	61	2023-09-25	2023-10-25	\N
89	6	235	2023-09-25	2023-10-25	\N
90	6	40	2023-09-25	2023-10-25	\N
91	22	199	2023-09-25	2023-10-25	\N
93	6	39	2023-09-25	2023-10-25	\N
\.


--
-- Data for Name: reserved_books; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.reserved_books (id, user_id, copy_id, reservation_date, reservation_end) FROM stdin;
177	6	415	2023-09-24	2023-10-01
179	22	182	2023-09-24	2023-10-01
181	22	214	2023-09-24	2023-10-01
187	6	111	2023-09-25	2023-10-02
188	6	228	2023-09-25	2023-10-02
189	6	191	2023-09-25	2023-10-02
193	6	12	2023-09-25	2023-10-02
194	6	62	2023-09-25	2023-10-02
195	6	194	2023-09-25	2023-10-02
196	21	195	2023-09-25	2023-10-02
\.


--
-- Data for Name: user_details; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.user_details (id, name, lastname, user_id) FROM stdin;
7	admin	admin	10
18	Jan	Drzewicz	21
12	Piotr	Zimirski	15
15	Piotr	Zimirski	18
19	Jakub	Sowa	22
4	Marta	Sienkiewicz	6
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: zimmer
--

COPY public.users (id, email, password, role) FROM stdin;
10	admin@admin.com	$2y$10$CBXnwj6.JQ4Jcms3X4b.keVps5.eNvwzLrrtj1OZAMQxwK0H.UCAu	admin
15	zimmer@mail.com	$2y$10$gjfG0pn3nbgyJFmb0/RWcO9WtoVvzmziigE/Q8pu3wRFX.4zVYhrO	admin
22	sowajakub@mail.com	$2y$10$KZ1ELoX/f2DMAMHGV8nxOOs/UEH6nMFf3DjmlYpX1JghcBK4lJyTe	user
6	marta.sienkiewicz@example.com	$2y$10$OAl7TCsEDsms2iTEXLLXbebAS56/bvMzdPWvFR8ujSO08YIYc16Ae	user
21	jdrzewicz@com.pl	$2y$10$nGs4RcD9f3IP6Av7d3PUZODwKioc.jzaqzKk3qBYctTSDHuSOHnj2	user
18	zimmer@poczta.com	$2y$10$TjO.5HOD46Txxjsko4cumebgpqWD5EwtkdI7aOovuviUSjyBnZ8NG	user
\.


--
-- Name: authors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.authors_id_seq', 55, true);


--
-- Name: book_copies_copy_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.book_copies_copy_id_seq', 433, true);


--
-- Name: books_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.books_id_seq', 55, true);


--
-- Name: borrowedbooks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.borrowedbooks_id_seq', 93, true);


--
-- Name: reservedbooks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.reservedbooks_id_seq', 196, true);


--
-- Name: userdetails_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.userdetails_id_seq', 19, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: zimmer
--

SELECT pg_catalog.setval('public.users_id_seq', 22, true);


--
-- Name: archive_loans archive_loans_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.archive_loans
    ADD CONSTRAINT archive_loans_pkey PRIMARY KEY (id);


--
-- Name: authors authors_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.authors
    ADD CONSTRAINT authors_pkey PRIMARY KEY (id);


--
-- Name: book_copies book_copies_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.book_copies
    ADD CONSTRAINT book_copies_pkey PRIMARY KEY (id);


--
-- Name: books books_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_pkey PRIMARY KEY (id);


--
-- Name: books_authors booksauthors_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.books_authors
    ADD CONSTRAINT booksauthors_pkey PRIMARY KEY (book_id, author_id);


--
-- Name: borrowed_books borrowedbooks_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.borrowed_books
    ADD CONSTRAINT borrowedbooks_pkey PRIMARY KEY (id);


--
-- Name: reserved_books reservedbooks_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.reserved_books
    ADD CONSTRAINT reservedbooks_pkey PRIMARY KEY (id);


--
-- Name: user_details userdetails_pkey; Type: CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.user_details
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
-- Name: book_copies after_delete_book_copies; Type: TRIGGER; Schema: public; Owner: zimmer
--

CREATE TRIGGER after_delete_book_copies AFTER DELETE ON public.book_copies FOR EACH ROW EXECUTE FUNCTION public.update_stock();

ALTER TABLE public.book_copies DISABLE TRIGGER after_delete_book_copies;


--
-- Name: book_copies after_insert_book_copies; Type: TRIGGER; Schema: public; Owner: zimmer
--

CREATE TRIGGER after_insert_book_copies AFTER INSERT ON public.book_copies FOR EACH ROW EXECUTE FUNCTION public.update_stock();

ALTER TABLE public.book_copies DISABLE TRIGGER after_insert_book_copies;


--
-- Name: books tr_update_availability; Type: TRIGGER; Schema: public; Owner: zimmer
--

CREATE TRIGGER tr_update_availability BEFORE INSERT OR UPDATE OF stock ON public.books FOR EACH ROW EXECUTE FUNCTION public.update_availability();


--
-- Name: books_authors trigger_create_book_copies; Type: TRIGGER; Schema: public; Owner: zimmer
--

CREATE TRIGGER trigger_create_book_copies AFTER INSERT ON public.books_authors FOR EACH ROW EXECUTE FUNCTION public.create_book_copies();


--
-- Name: books_authors trigger_delete_book_copies; Type: TRIGGER; Schema: public; Owner: zimmer
--

CREATE TRIGGER trigger_delete_book_copies AFTER DELETE ON public.books_authors FOR EACH ROW EXECUTE FUNCTION public.delete_book_copies();


--
-- Name: book_copies trigger_update_book_stock; Type: TRIGGER; Schema: public; Owner: zimmer
--

CREATE TRIGGER trigger_update_book_stock AFTER UPDATE OF status ON public.book_copies FOR EACH ROW EXECUTE FUNCTION public.update_book_stock();


--
-- Name: archive_loans archive_loans_book_copies_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.archive_loans
    ADD CONSTRAINT archive_loans_book_copies_id_fk FOREIGN KEY (copy_id) REFERENCES public.book_copies(id);


--
-- Name: archive_loans archive_loans_users_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.archive_loans
    ADD CONSTRAINT archive_loans_users_id_fk FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: book_copies book_copies_book_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.book_copies
    ADD CONSTRAINT book_copies_book_id_fkey FOREIGN KEY (book_id) REFERENCES public.books(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: books_authors booksauthors_author_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.books_authors
    ADD CONSTRAINT booksauthors_author_id_fkey FOREIGN KEY (author_id) REFERENCES public.authors(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: books_authors booksauthors_book_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.books_authors
    ADD CONSTRAINT booksauthors_book_id_fkey FOREIGN KEY (book_id) REFERENCES public.books(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: borrowed_books borrowed_books_book_copies_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.borrowed_books
    ADD CONSTRAINT borrowed_books_book_copies_id_fk FOREIGN KEY (copy_id) REFERENCES public.book_copies(id);


--
-- Name: borrowed_books borrowedbooks_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.borrowed_books
    ADD CONSTRAINT borrowedbooks_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: reserved_books reserved_books_book_copies_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.reserved_books
    ADD CONSTRAINT reserved_books_book_copies_id_fk FOREIGN KEY (copy_id) REFERENCES public.book_copies(id);


--
-- Name: reserved_books reservedbooks_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.reserved_books
    ADD CONSTRAINT reservedbooks_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: user_details userdetails_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: zimmer
--

ALTER TABLE ONLY public.user_details
    ADD CONSTRAINT userdetails_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

