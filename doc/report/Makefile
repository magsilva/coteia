ROOT = root

all: clean pdf

distclean: clean
	rm -f  *.pdf *.ps

clean:
	rm -f *.log *.aux *.toc *.dvi *.bbl *.blg *.*~* *.lof *.lot *.cb *.backup

ps:
	latex $(ROOT)
	bibtex $(ROOT)
	latex $(ROOT)
	latex $(ROOT)
	latex $(ROOT)
	dvips $(ROOT) -o $(ROOT).ps

pdf:
	pdflatex $(ROOT)
	bibtex $(ROOT)
	pdflatex $(ROOT)
	pdflatex $(ROOT)
	pdflatex $(ROOT)
