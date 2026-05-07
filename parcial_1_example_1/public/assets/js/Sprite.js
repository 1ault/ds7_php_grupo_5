export class Sprite {
    constructor({ src }) {
        this.image = new Image();
        this.loaded = false;
        this.image.onload = () => { this.loaded = true; };
        this.image.src = src;
    }

    draw(ctx, x, y, width, height) {
        if (!this.loaded) return;
        if (width && height) {
            ctx.drawImage(this.image, x, y, width, height);
        } else {
            ctx.drawImage(this.image, x, y);
        }
    }
}