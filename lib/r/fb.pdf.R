#get inputs
args <- commandArgs(TRUE)
dist <- args[1]
par1 <- as.numeric(args[2])
par2 <- as.numeric(args[3])
lsq  <- as.numeric(args[4])

#------------------------------------------------
.libPaths("/home/webdev/R/x86_64-redhat-linux-gnu-library/2.12")
library(sp)
library(gstat)
library(eeVariogram)

sig.fig = 3

fb.pdf = pdf.feedback(dist,par1,par2,lsq,sig.fig)
if (dist == "Normal"){
min = qnorm(0.0001, par1, par2)
max = qnorm(0.9999, par1, par2)
p1  = qnorm(0.1, par1, par2)
p2  = qnorm(0.9, par1, par2)
}else{if (dist == "Lognormal"){
min = qlnorm(0.0001, par1, par2)
max = qlnorm(0.9999, par1, par2)
p1  = qlnorm(0.1, par1, par2)
p2  = qlnorm(0.9, par1, par2)}}


x<-seq(from=max,to=min,length=102)[2:101]

p   = unlist(fb.pdf[1])
q1  = unlist(fb.pdf[2])
q2  = unlist(fb.pdf[3])
med = unlist(fb.pdf[4])


write(x,"", ncolumns = 100)
write(p,"",ncolumns = 100)
write(q1,"")
write(q2,"")
write(med,"")
write(p1,"")
write(p2,"")
