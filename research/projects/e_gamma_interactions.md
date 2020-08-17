## Research project
<hr>

### Monte Carlo Electron-Photon Interactions with Pair Formation

The [write-up](e_gamma_write_up.pdf)

**Overview**

(I completed this project during my visit to Penn State in Summer 2015.)

In this project, I wrote an adaptive Monte Carlo code in C++ to solve the time-dependent kinetic equations for relativistic electron in an isotropic
photon field. The processes such as synchrotron radiation, inverse Compton scattering, gamma-gamma pair production and pair annihilation are included. The code was then used in other projects.

The figure below shows one output of this code. In this example, initially the magnetic field in the simulated box is 490 micro Gauss and there is no photons. We inject ralativistic electrons with a power-law distribution to this region and solve the differential equations to obtain the evolution of the radiation spectrum. As time goes on, we find that the inverse Compton component (the right hump) becomes increasingly important and the kinitic energy of electrons is gradually transferred to the raidation field (as the parameter &lambda; increases to 1).
<p>
<img align="left" src="figs/e_gamma1.png" alt="drawing" width="500"/>
<em>Snapshots of the emission spectra at various times.</em>
 </p>
