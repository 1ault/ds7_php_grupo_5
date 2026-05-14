import { Sprite } from "./Sprite.js";

export class GameObject {
    constructor({ x = 0, y = 0, src = "" }) {
        this.x = x;
        this.y = y;
        this.sprite = new Sprite({ src });
    }

    draw(ctx) {
        this.sprite.draw(ctx, this.x, this.y);
    }
}