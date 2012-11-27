#get inputs
args <- commandArgs(TRUE)
a = args[1]
eval(parse(text=a,srcfile=NULL))
N.experts = all$N.experts
E.Qs.min  = all$min
E.Qs.max  = all$max
Dist.type = all$dist
par1      = all$par1
par2      = all$par2

#----------------------------------
library(sp)
library(gstat)
library(eeVariogram)

E.quantiles = matrix(0,N.experts,500)

Lo = min(E.Qs.min)
Up = max(E.Qs.max)

x = seq(Lo,Up,length=502)[2:501]

for (n in 1:N.experts){
 if (Dist.type[n] == "Normal"){
 E.quantiles[n,] = pnorm(x,par1[n],par2[n])
 } else {
 E.quantiles[n,] = plnorm(x,par1[n],par2[n])
 }
}
#equal - weighted pooling all quantiles
E.Qc = apply(E.quantiles,2,mean)

#calculate the parameters of E.Qc 
interQs = c(0.25,0.5,0.75)
Qs = approx(x=E.Qc,y=x,xout=interQs)$y
E.margs = pdf.fitting(Lo,Up,Qs[1],Qs[2],Qs[3]) 

fQ = Qs[1]
tQ = Qs[3]
dist = E.margs$dist
pars1 = E.margs$pars[1]
pars2 = E.margs$pars[2]
lsq   = E.margs$lsq

write(dist, "") 
write(pars1, "")
write(pars2, "")
write(lsq, "")
write(Lo, "") 
write(Up, "")
write(N.experts, "")
write(fQ, "") 
write(tQ, "") 
