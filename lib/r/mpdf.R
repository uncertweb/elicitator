#getting input
options(warn=-1)
args <- commandArgs(TRUE)
max <- as.numeric(args[1])
min <- as.numeric(args[2])
med <- as.numeric(args[3])
fq  <- as.numeric(args[4])
tq  <- as.numeric(args[5])

#------------------
# .libPaths("/home/webdev/R/x86_64-redhat-linux-gnu-library/2.12")
.libPaths("/usr/local/Cellar/r/2.13.1/R.framework/Versions/2.13/Resources/library")

suppressPackageStartupMessages(library(sp))
suppressPackageStartupMessages(library(gstat))
suppressPackageStartupMessages(library(eeVariogram))


E.marg = pdf.fitting(min,max,fq,med,tq)

dist = E.marg$dist
pars1 = E.marg$pars[1]
pars2 = E.marg$pars[2]
lsq   = E.marg$lsq

write(dist, "")
write(pars1, "")
write(pars2, "")
write(lsq, "")