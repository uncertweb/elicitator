# Parameters of max, min and pdf for each expert: expert.pdf
#expert.pdf<-list(exp=c(1,2), min=c(0,12),max=c(12,56), pdf=c("Normal", "Gamma"),par1=c(3.264238,6.66), par2=c(2.795621,3.75))
args = commandArgs(TRUE)
eval(parse(text= args[1]))
degfree<-3 # for fitting student t distribution
######################### define number of experts #############################
 N.experts = length(expert.pdf$exp)
############# Initialize parameters ############################################
f.com<-matrix(0,N.experts,100)
maximum<-matrix(0,N.experts,1) 
minimum<-matrix(0,N.experts,1) 
for(i in 1: N.experts)
{
 minimum[i,1]=expert.pdf$min[i]
 maximum[i,1]=expert.pdf$max[i]
} 
Up=max(maximum)
Lo=min(minimum)
x<-seq(from=Lo,to=Up,length=102)[2:101]
######################### Equal weight pooling #################################
for(i in 1: N.experts) 
{ 
  
 ifelse(expert.pdf$pdf[[i]] == "Beta", f.com[i,]<-1/(expert.pdf$max[i]-expert.pdf$min[i])*pbeta((x-expert.pdf$min[i])/(expert.pdf$max[i]-expert.pdf$min[i]),expert.pdf$par1[i],expert.pdf$par2[i]), NA) 
 ifelse(expert.pdf$pdf[[i]] == "Normal", f.com[i,]<-pnorm(x,expert.pdf$par1[i],expert.pdf$par2[i]),NA)
 ifelse(expert.pdf$pdf[[i]] == "Gamma", f.com[i,]<-pgamma(x,expert.pdf$par1[i],scale=expert.pdf$par2[i]),NA)
 ifelse(expert.pdf$pdf[[i]] == "LogNormal", f.com[i,]<-plnorm(x,expert.pdf$par1[i],expert.pdf$par2[i]),NA)
 ifelse(expert.pdf$pdf[[i]] == "LogStudentT", f.com[i,]<-pt((log(x)-expert.pdf$par1[i])/expert.pdf$par2[i],degfree)/(expert.pdf$par2[i]*x),NA)
 ifelse(expert.pdf$pdf[[i]] == "StudentT", f.com[i,]<-pt((x-expert.pdf$par1[1])/expert.pdf$par2[i],degfree)/(expert.pdf$par2[i]),NA)
}

########## Calculate linear pool from best fitting density for each expert ######
linear.pool<-apply(f.com,2,mean)

interQs = c(0.25,0.5,0.75)
Qs = approx(x=linear.pool,y=x,xout=interQs)$y
l=Qs[1]
m=Qs[2]
u=Qs[3]
# 1 Shared functions for least squares fitting 

betaerror<-function(x,elicited,probabilities){
a<-exp(x[1])
b<-exp(x[2])
sum((pbeta(elicited,a,b)-probabilities)^2)
}

gammaerror<-function(x,elicited,probabilities){
a<-exp(x[1])
b<-exp(x[2])
sum((pgamma(elicited,a,b)-probabilities)^2)
}

normalerror<-function(x,elicited,probabilities){
a<-x[1]
b<-exp(x[2])
sum((pnorm(elicited,a,b)-probabilities)^2)
}

lnormerror<-function(x,elicited,probabilities){
a<-x[1]
b<-exp(x[2])
sum((plnorm(elicited,a,b)-probabilities)^2)
}

logterror<-function(x,elicited,probabilities,degreesfreedom){
a<-x[1]
b<-exp(x[2])
sum((pt((log(elicited)-a)/b,degreesfreedom)-probabilities)^2)
}

t.error<-function(x,elicited,probabilities,degreesfreedom){
a<-x[1]
b<-exp(x[2])
sum((pt((elicited-a)/b,degreesfreedom)-probabilities)^2)
}

# 2 Setting initial values 
all.distributions=c("Beta","Normal","Gamma","LogNormal","LogStudentT","StudentT")
degfree<-3 # for fitting student t distribution
s<-qnorm(1-(0.5/2))# getting upper quartile of standard normal distribution (with mean=0 and std=1)
v<-((u-l)/(2*s))^2 # initial with normal distribution assumption: Q3-Q1=2.s.sigma with s=0.6744898

# 3 Fit pdf 
# Fit a beta distribution
# Not sure this is working correctly...
m.new<-(m-Lo)/(Up-Lo)
v.new<-v/(Up-Lo)^2
elicited.new<-(c(Lo,l,m,u,Up)-Lo)/(Up-Lo)
alpha<-m.new^3/v.new*(1/m.new-1)-m.new
bet<-alpha/m.new-alpha
betafit<-optim(c(log(alpha),log(bet)),betaerror,elicited=c(Lo,l,m,u,Up),probabilities=c(0,0.25,0.5,0.75,1))
betaparameters<-exp(betafit$par)
betalsq<-betafit$value
distbeta<-"Beta"

# Fit a normal distribution
normalfit<-optim(c(m,0.5*log(v)),normalerror,elicited=c(Lo,l,m,u,Up),probabilities=c(0,0.25,0.5,0.75,1))
normalparameters<-c(normalfit$par[1],exp(normalfit$par[2]))
normallsq<-normalfit$value
distnorm<-"Normal"

if(Lo>=0){
# Fit a gamma distribution
gammafit<-optim(c(log(m^2/v),log(m/v)),gammaerror,elicited=c(Lo,l,m,u,Up),probabilities=c(0,0.25,0.5,0.75,1))
gammaparameters<-exp(gammafit$par)
gammalsq<-gammafit$value
distgam<-"Gamma"

# Fit a log normal distribution
std<-((log(u)-log(l))/(2*s))
lognormalfit<-optim(c(log(m),log(std)),lnormerror,elicited=c(Lo,l,m,u,Up), probabilities=c(0,0.25,0.5,0.75,1))
lnormparameters<-lognormalfit$par
lnormparameters[2]<-exp(lnormparameters[2])
lognormallsq<-lognormalfit$value
distlogn<-"LogNormal"

# Fit a log t distribution
std<-((log(u)-log(l))/(2*s))
logtfit<-optim(c(log(m),log(std)),logterror,elicited=c(Lo,l,m,u,Up),probabilities=c(0,0.25,0.5,0.75,1),degreesfreedom=degfree)
logtparameters<-logtfit$par
logtparameters[2]<-exp(logtparameters[2])
logtlsq<-logtfit$value
distlogt<-"LogStudentT"}else{
gammaparameters<-NA
gammalsq<-10
lnormparameters<-NA
lognormallsq<-10
logtparameters<-NA
logtlsq<-10}

# Fit a t distribution
tfit<-optim(c(m,0.5*log(v)),t.error,elicited=c(Lo,l,m,u,Up),probabilities=c(0,0.25,0.5,0.75,1),degreesfreedom=degfree)
tparameters<-c(tfit$par[1],exp(tfit$par[2]))
tlsq<-tfit$value
diststudt<-"StudentT"
all.lsq<-c(betalsq,normallsq,gammalsq,lognormallsq,logtlsq,tlsq)

### 4 Identify best fitting pdf
x<-seq(from=Lo,to=Up,length=102)[2:101]
f.best.fitting<-rep(0,1,100)
f.beta<-1/(Up-Lo)*dbeta((x-Lo)/(Up-Lo),betaparameters[1],betaparameters[2])
 f.normal<-dnorm(x,normalparameters[1],normalparameters[2])
 if(Lo>=0){
  f.gamma<-dgamma(x,gammaparameters[1],gammaparameters[2])
  f.lognormal<-dlnorm(x,lnormparameters[1],lnormparameters[2])
  f.logt<-dt((log(x)-logtparameters[1])/logtparameters[2],degfree)/(logtparameters[2]*x)
 }
f.t<-dt((x-tparameters[1])/tparameters[2],degfree)/(tparameters[2])
f.best.fitting<-switch(which(all.lsq==min(all.lsq)),f.beta, f.normal, f.gamma, f.lognormal ,f.logt ,f.t)
best.fitting.names<-switch(which(all.lsq==min(all.lsq)),"Beta","Normal","Gamma","LogNormal","LogStudentT", "StudentT")

write(best.fitting.names, "")
if(best.fitting.names == "Normal") {
    parameters = normalparameters
} else if(best.fitting.names == "Beta") {
    parameters = betaparameters
} else if(best.fitting.names == "Gamma") {
    parameters = gammaparameters
} else if(best.fitting.names == "LogNormal") {
    parameters = lnormparameters
} else if(best.fitting.names == "LogStudentT") {
    parameters = logtparameters
} else if(best.fitting.names == "StudentT") {
    parameters = tparameters
}
write(parameters, "")