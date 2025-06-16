// src/pages/LandingPage.tsx

import React from 'react';
import { useNavigate } from 'react-router-dom';
import logo from '../assets/logocodequest.png';
import medallon from '../assets/medallon.png';
import pergamino from '../assets/pergamino.png';
import './LandingPage.css';

const LandingPage: React.FC = () => {
  const navigate = useNavigate();

  return (
    <div className="landing-content">
      <img src={logo} alt="Logo CodeQuest" className="landing-logo animate-logo-magic-slow" />

      <h1 className="rpg-title">
        FORJA TU DESTINO <br /> COMO HÉROE DEL CÓDIGO
      </h1>

      <p className="rpg-subtitle">
        En las tierras místicas de CodeQuest, cada algoritmo es un hechizo,<br />
        cada función es un arma, y cada proyecto completado te acerca a la leyenda.
      </p>

      <div className="landing-buttons-row">
        <img
          src={pergamino}
          alt="Comenzar la búsqueda"
          className="landing-btn left"
          onClick={() => navigate('/register')}
        />
        <img
          src={medallon}
          alt="Entrar al reino"
          className="landing-btn right"
          onClick={() => navigate('/login')}
        />
      </div>
    </div>
  );
};

export default LandingPage;
