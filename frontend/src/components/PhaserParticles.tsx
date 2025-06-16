import React, { useEffect, useRef } from 'react';
// Importa el tipo solo como tipo
import type { MutableRefObject } from 'react';
import Phaser from 'phaser';

// Lista de iconos mágicos de programación
const PARTICLE_SYMBOLS = ['{', '}', '<', '>', ';', 'λ', '⚡', '()', '[]', '/'];

type InputPosition = { x: number; y: number };

type ParticleSceneConfig = {
  inputPositions: InputPosition[];
};

class ParticleScene extends Phaser.Scene {
  create() {
    this.time.addEvent({
      delay: 300,
      loop: true,
      callback: () => {
        for (let i = 0; i < 4; i++) {
          const symbol = Phaser.Utils.Array.GetRandom(PARTICLE_SYMBOLS);
          const x = Phaser.Math.Between(40, this.sys.game.config.width as number - 40);
          const y = Phaser.Math.Between(60, this.sys.game.config.height as number - 60);
          const text = this.add.text(x, y, symbol, {
            font: '24px Cinzel, serif',
            color: '#ffe6a7',
            stroke: '#000',
            strokeThickness: 2,
          });
          // Movimiento más largo y aleatorio
          const yOffset = Phaser.Math.Between(60, 180);
          const xOffset = Phaser.Math.Between(-60, 60);
          this.tweens.add({
            targets: text,
            y: y - yOffset,
            x: x + xOffset,
            alpha: 0,
            duration: Phaser.Math.Between(1800, 3200),
            ease: 'Cubic.easeOut',
            onComplete: () => text.destroy(),
          });
        }
      },
    });
  }
}

type PhaserParticlesProps = {
  active: boolean;
};

const PhaserParticles: React.FC<PhaserParticlesProps> = ({ active }) => {
  const phaserRef = useRef<HTMLDivElement>(null);
  const gameRef = useRef<Phaser.Game | null>(null);

  useEffect(() => {
    if (active && phaserRef.current && !gameRef.current) {
      gameRef.current = new Phaser.Game({
        type: Phaser.CANVAS,
        width: window.innerWidth,
        height: window.innerHeight,
        transparent: true,
        parent: phaserRef.current,
        scene: ParticleScene,
      });
    }
    if (!active && gameRef.current) {
      gameRef.current.destroy(true);
      gameRef.current = null;
    }
    return () => {
      if (gameRef.current) {
        gameRef.current.destroy(true);
        gameRef.current = null;
      }
    };
  }, [active]);

  return (
    <div
      ref={phaserRef}
      style={{
        position: 'fixed',
        left: 0,
        top: 0,
        width: '100vw',
        height: '100vh',
        pointerEvents: 'none',
        zIndex: 30,
      }}
    />
  );
};

export default PhaserParticles;
